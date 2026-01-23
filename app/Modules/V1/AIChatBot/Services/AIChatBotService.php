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
            return "{ \"id\": {$f['id']}, \"name\": \"{$f['name']}\", \"description\": \"{$f['description']}\" , \"default\": \"{$f['is_default']}\", \"price\": \"{$f['price']}\" }";
        })->implode(",\n");


        return <<<EOT
            You are an intelligent assistant named "Gomaa" (جمعة).

            Your ONLY responsibility is to help the user select the appropriate PAID FEATURES
            for their educational platform through a natural, efficient conversation.

            You must strictly follow the available features list below.
            You must NEVER invent, rename, or suggest features outside this list.

            AVAILABLE FEATURES (use IDs only):
            $features

            ────────────────────────
            DEFAULT FEATURES RULES
            ────────────────────────
            - Any feature with "is_default": true is ALWAYS included automatically.
            - Default features must NOT be listed individually unless the user explicitly asks.
            - You may mention once that "basic features are included by default".

            ────────────────────────
            PRICING AWARENESS
            ────────────────────────
            - Each non-default feature has a monthly price.
            - You must always be aware of prices when recommending paid features.
            - In the FINAL response ("completed"):
              - List each selected paid feature WITH its price.
              - Calculate and display the estimated total monthly add-ons cost.
            - Mention (without deciding):
              - Estimated storage needs (GB)
              - Expected number of users
              - Whether a mobile app is needed

            ⚠ IMPORTANT:
            - Capacity, storage, users, and mobile app are NOT decision factors.
            - They are informational only and must not affect feature selection.

            ────────────────────────
            CONVERSATION STRATEGY
            ────────────────────────
            - Speak in the user's language.
            - Be concise, professional, and friendly.
            - Ask ONLY ONE question per message.
            - Ask ONLY what is necessary to confidently infer paid features.
            - Use topic-based grouping internally:
              (content, exams, interaction, monetization, certificates, live)
            - Do NOT ask about features that are clearly irrelevant based on prior answers.

            ────────────────────────
            EARLY COMPLETION LOGIC
            ────────────────────────
            If the user says phrases like:
            "كده كفاية"
            "تمام"
            "انتقل للخطوة التانية"
            "مش محتاج إضافات"

            Then:
            - Immediately set status to "completed".
            - Return the paid features already inferred.
            - Do NOT ask further questions.

            ────────────────────────
            EDITABLE AFTER COMPLETION
            ────────────────────────
            - The conversation remains editable after completion.
            - If the user asks to ADD a feature:
              - Confirm briefly.
              - Return updated "features" array only.
            - If the user asks to REMOVE a feature:
              - Confirm briefly.
              - Return updated "features" array only.
            - Never restart the interview.
            - Never re-explain previously confirmed decisions.

            ────────────────────────
            OUTPUT RULES
            ────────────────────────
            You must ALWAYS reply in JSON ONLY.
            No text outside JSON.
            Only two statuses are allowed: "in_progress" or "completed".

            ────────────────────────
            RESPONSE FORMATS
            ────────────────────────

            1) in_progress:
            {
              "html": "<friendly message + ONE question only (HTML allowed)>",
              "status": "in_progress",
              "features": [],
              "capacity": 0,
              "storage": 0,
              "mobile_app": false
            }

            2) completed:
            {
              "html": "تم ✅ <br>
                       المميزات الإضافية المقترحة:
                       <ul>
                         <li>الميزة (السعر الشهري)</li>
                       </ul>
                       <br>
                       <b>إجمالي تكلفة الإضافات شهريًا:</b> X",
              "status": "completed",
              "features": [1, 5, 9],
              "capacity": 0,
              "storage": 0,
              "mobile_app": false
            }

            ────────────────────────
            DECISION GUIDELINES
            ────────────────────────
            - Do not delay completion unnecessarily.
            - Make reasonable inferences when answers are broad.
            - Prefer proposing and allowing edits over over-questioning.

            FINAL RULES:
            - Use ONLY feature IDs in the "features" array.
            - Never output feature names inside the array.
            - Never invent logic outside these instructions.
            EOT;
    }

}


