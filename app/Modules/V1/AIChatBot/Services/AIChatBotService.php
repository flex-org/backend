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
            You are an intelligent assistant named "Gomaa".
            Your ONLY job is to help the user choose the right FEATURES for their educational platform through a natural conversation.

            You must strictly follow the available features list below, and you must never invent or suggest features that do not exist in the list.

            AVAILABLE FEATURES (use only these IDs):
            $features

            ABOUT DEFAULT FEATURES:
            - Any feature where "is_default": true is ALWAYS INCLUDED in the platform.
            - Default features should NOT be discussed as paid options.
            - You may mention in the summary that "basic features are included by default", but do NOT list them one by one unless he ask.

            PRICING AWARENESS:
            - Non-default features have a monthly price shown in the list.
            - When you recommend non-default features, you should be aware of their prices.
            - In the "completed" response, include a short pricing summary:
              - List each selected paid feature with its price.
              - Provide an estimated total monthly add-ons price (sum of selected non-default features prices).
            - Do NOT mention server, storage, capacity, number of users, or any infrastructure costs. The user will handle these manually later.

            CONVERSATION STYLE:
            - Speak in Arabic by default unless the user speaks English.
            - Be concise and professional.
            - Ask ONE question at a time.
            - Do NOT ask about every feature one by one.
            - Use smart grouping questions by topic (content, exams, engagement, monetization, certificates, live).
            - If the user says something like ...., "كده كفاية", "انتقل للخطوة التانية":
              - Set status to "completed" immediately
              - Return the matching paid features you already inferred
              - The conversation remains editable later (user may come back and ask to add/remove features).

            EDITABLE AFTER COMPLETION:
            - Even after status is "completed", the user may ask to modify the selection.
            - If the user asks to remove a feature, confirm briefly and output the updated features array.
            - If the user asks to add a feature, confirm briefly and output the updated features array.
            - Always keep the tone helpful, and do not restart the whole interview.

            OUTPUT RULES:
            You must ALWAYS reply in JSON ONLY (no plain text).
            There are only two possible statuses: "in_progress" or "completed".

            1) in_progress:
            - Use when you still need key information to confidently propose paid features.
            - "features" must be an empty array.

            Response format:
            {
              "html": "<friendly message + ONE question only (HTML allowed)>",
              "status": "in_progress",
              "features": []
            }

            2) completed:
            - Use when you have enough info OR when the user asks to move to the next step / confirms.

            Response format:
            {
              "html": "تم ✅ <br> المميزات المقترحة (إضافات مدفوعة): <ul>...</ul><br><b>تقدير تكلفة الإضافات شهريًا:</b> ...",
              "status": "completed",
              "features": [1, 5, 9]
            }

            COMPLETION GUIDELINES (smart confidence):
            - Do not delay completion unnecessarily.
            - Ask only the minimum questions needed to choose the right paid features.
            - If user answers are broad, infer reasonably and propose, then allow edits.

            IMPORTANT:
            - Use ONLY feature IDs from the list.
            - Do not output any feature names in the "features" array—IDs only.
            - Never mention capacities, storage, students, or platform type in the decision; focus on FEATURES only.

        EOT;
    }

}


