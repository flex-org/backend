<?php
namespace App\Modules\V1\AIChatBot\Services;

use Gemini;
use Gemini\Enums\Role;
use Gemini\Data\Schema;
use Gemini\Data\Content;
use Gemini\Enums\DataType;
use Gemini\Data\GenerationConfig;
use Gemini\Enums\ResponseMimeType;
use Gemini\Resources\GenerativeModel;
use Illuminate\Support\Facades\Cache;
use App\Modules\V1\Features\Services\FeatureService;
use Symfony\Component\HttpKernel\Exception\TooManyRequestsHttpException;

class AIChatBotService
{
    private $usageLimit;
    private GenerativeModel $model;
    
    public function __construct(private FeatureService $featureService)
    {
        $client = Gemini::client(config('gemini.api_key'));
        $this->usageLimit = config('gemini.daily_chat_tokens_limit');

        $systemInstruction = Content::parse(
            part: $this->modelInstructions(),
            role: Role::MODEL
        );

        $this->model = $client->generativeModel('gemini-2.5-flash')
            ->withSystemInstruction($systemInstruction);
    }

    public function sendMessage(string $message, int $userId)
    {
        $data = $this->getUserSession($userId);
        $content = Content::parse(part: $message, role: Role::USER);

        $data['history'][] = $content;
        $this->checkTokenLimit($this->model, $content, $data, $userId);

        $response = $this->getModelReply($this->model, $data);

        if (($response->status ?? null) === 'completed') {
            $response->features = $this->mapFeatures($response->features);
        }

        $data['history'][] = Content::parse(part: $response->html, role: Role::MODEL);
        $this->updateUserSession($userId, $data);

        return $response;
    }

    private function getUserSession(int $userId): array
    {
        $key = "user:{$userId}:" . now()->toDateString();

        return cache()->get($key, [
            'history' => [],
            'tokens'  => 0,
        ]);
    }

    private function checkTokenLimit(GenerativeModel $model, $content, array &$data, int $userId): void
    {
        if ($data['tokens'] >= $this->usageLimit) {
            throw new TooManyRequestsHttpException();
        }

        $tokenCountResponse = $model->countTokens($content);
        $data['tokens'] += $tokenCountResponse->totalTokens;

        $this->updateUserSession($userId, $data);
    }

    private function updateUserSession(int $userId, array $data): void
    {
        cache()->put("user:{$userId}:" . now()->toDateString(), $data, now()->endOfDay());
    }

    private function getModelReply($model, array $data)
    {
        $result = $model->withGenerationConfig(
            generationConfig: new GenerationConfig(
                responseMimeType: ResponseMimeType::APPLICATION_JSON,
                responseSchema: new Schema(
                    type: DataType::OBJECT,
                    properties: [
                        'html' => new Schema(type: DataType::STRING),
                        'status' => new Schema(type: DataType::STRING),
                        'features' => new Schema(
                            type: DataType::ARRAY,
                            items: new Schema(type: DataType::TYPE_UNSPECIFIED)
                        )
                    ],
                    required: ['html', 'status', 'features']
                )
            )
        )->startChat(history: $data['history'])->sendMessage();

        return $result->json();
    }

    private function mapFeatures(array $keys): array
    {
        $allFeatures = Cache::rememberForever('features', function () {
            return $this->featureService->getAll(true);
        });

        return collect($allFeatures)
            ->filter(fn($f) => in_array($f['id'], $keys))
            ->map(fn($f) => [
                'id' => $f['id'],
                'name' => $f['name'],
                'price' => $f['price'],
                'description' => $f['description'],
                'icon' => $f['icon'],
            ])
            ->values()
            ->toArray();
    }

    private function modelInstructions(): string
    {
        $features = Cache::rememberForever('features', function () {
            return $this->featureService->getAll(true);
        });

        $featureList = collect($features)->map(function ($f) {
            return "{ \"id\": {$f['id']}, \"name\": \"{$f['name']}\", \"description\": \"{$f['description']}\" }";
        })->implode(",\n");


        return <<<EOT
            You are an intelligent assistant named "Gomaa" that helps business owners describe their educational platform needs in natural conversation
            and automatically determines which features from the available list match those needs.

            AVAILABLE FEATURES (use only the IDs from this list when outputting features):
            [$featureList]


            ### YOUR ROLE
            - You act like a professional consultant for an educational platform builder.
            - Start the conversation naturally with a **warm opening question** to learn about the user’s goals.  
            - The user will describe what kind of platform they want, who they target, and what they plan to offer.
            - Your job is to understand their intent, goals, and workflow — then infer which features are needed from the available list.
            - You should never list multiple questions in one message.  
            Focus on **one specific topic per question** (e.g., content type first, then live sessions, then exams...).
            - You can ask short, natural clarifying questions **only if necessary**.
            - You never suggest or invent features that are not in the available list.
            - You never create new names or feature types.


            ### OUTPUT RULES
            You always reply in **JSON** (no plain text).
            There are only two possible reply formats:

            1️⃣ When you are still understanding the user or not 100% sure:
            ```json
            {
                "html": "<your friendly message or clarifying question (HTML allowed)>",
                "status": "in_progress",
                "features": [] // always empty when status is "in_progress"
            }
            
            ### COMPLETION LOGIC
            - Do NOT mark the conversation as "completed" until you are **completely confident** that you understood the full set of the user's needs.
            - You must confirm or ask about **each relevant area** (like content, interaction, tracking, monetization, etc.) before completing.
            - You are required to ask about **every available feature** from the list one by one, unless the user already mentioned it clearly.
            - You must **receive a clear yes/no or equivalent answer** from the user for each feature before finalizing.
            - If the user’s description sounds partial, uncertain, or general, continue asking clarifying questions instead of completing.
            - Only set `"status": "completed"` when you have explicitly confirmed the presence or absence of all available features.
            - Only set fetures ids in features array when you have explicitly confirmed the presence or absence of all available features.
            - When you are confident that you fully understand the user's needs, include in "features" an array of the numeric IDs of the relevant features (based on the list above).
            - Always return "features" when "status" is "completed". Do not leave it empty.
            - The "features" array must contain the IDs only (e.g. [1, 5, 9]).

            ```json
            {
                "html": "تم فهم جميع احتياجاتك ✅ <br> المنصة الخاصة بك ستحتاج إلى:<br><ul><li>اسم الميزة الأولى</li><li>اسم الميزة الثانية</li></ul>",
                "status": "completed",
                "features": [ 1, 2 ] // array of feature IDs from the available list,
            }

        EOT;
    }

}


