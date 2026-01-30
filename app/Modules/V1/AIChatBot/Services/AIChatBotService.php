<?php

namespace App\Modules\V1\AIChatBot\Services;

use App\Modules\V1\Features\Services\FeatureService;
use Gemini;
use Gemini\Enums\Role;
use Gemini\Data\Schema;
use Gemini\Data\Content;
use Gemini\Enums\DataType;
use Gemini\Data\GenerationConfig;
use Gemini\Enums\ResponseMimeType;
use Gemini\Resources\GenerativeModel;
use Illuminate\Support\Collection;
use Symfony\Component\HttpKernel\Exception\TooManyRequestsHttpException;

class AIChatBotService
{
    private int $usageLimit;
    private GenerativeModel $model;
    private Collection $allFeatures;
    private Collection $selectedFeatures;

    public function __construct()
    {
        $this->usageLimit = config('gemini.daily_chat_tokens_limit');
    }

    public function sendMessage(string $message, int $userId, Collection $allFeatures, Collection $selectedFeatures): array
    {
        $this->allFeatures = $allFeatures;
        $this->selectedFeatures = $selectedFeatures;

        $this->initializeModel();

        $data = $this->getUserSession($userId);
        $previousFeatureIds = $data['selected_feature_ids'] ?? [];

        $content = Content::parse(part: $message);
        $data['history'][] = $content;

        $this->checkTokenLimit($this->model, $content, $data, $userId);

        $response = $this->getModelReply($data);

        $currentFeatureIds = $response->features ?? [];
        $newlyAddedIds = array_values(array_diff($currentFeatureIds, $previousFeatureIds));

        $mappedFeatures = $this->mapFeatures($currentFeatureIds);

        $data['history'][] = Content::parse(part: $response->html, role: Role::MODEL);
        $data['selected_feature_ids'] = $currentFeatureIds;
        $this->updateUserSession($userId, $data);

        return [
            'html' => $response->html,
            'status' => $response->status ?? 'in_progress',
            'features' => $mappedFeatures,
            'newly_added' => $this->mapFeatures($newlyAddedIds),
        ];
    }

    private function initializeModel(): void
    {
        $client = Gemini::client(config('gemini.api_key'));

        $systemInstruction = Content::parse(
            part: $this->buildSystemPrompt(),
            role: Role::MODEL
        );

        $this->model = $client->generativeModel('gemini-2.0-flash')
            ->withSystemInstruction($systemInstruction);
    }

    private function getUserSession(int $userId): array
    {
        $key = "user:{$userId}:chat:" . now()->toDateString();

        return cache()->get($key, [
            'history' => [],
            'tokens' => 0,
            'selected_feature_ids' => $this->selectedFeatures->pluck('id')->toArray(),
        ]);
    }

    private function checkTokenLimit(GenerativeModel $model, Content $content, array &$data, int $userId): void
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
        cache()->put("user:{$userId}:chat:" . now()->toDateString(), $data, now()->endOfDay());
    }

    private function getModelReply(array $data): object
    {
        $result = $this->model->withGenerationConfig(
            generationConfig: new GenerationConfig(
                responseMimeType: ResponseMimeType::APPLICATION_JSON,
                responseSchema: new Schema(
                    type: DataType::OBJECT,
                    properties: [
                        'html' => new Schema(
                            type: DataType::STRING,
                            description: 'HTML formatted response message to display to user'
                        ),
                        'status' => new Schema(
                            type: DataType::STRING,
                            description: 'Conversation status: in_progress or completed'
                        ),
                        'features' => new Schema(
                            type: DataType::ARRAY,
                            items: new Schema(type: DataType::INTEGER),
                            description: 'Array of all selected feature IDs (cumulative)'
                        ),
                    ],
                    required: ['html', 'status', 'features']
                )
            )
        )->startChat(history: $data['history'])->sendMessage();

        return $result->json();
    }

    private function mapFeatures(array $featureIds): array
    {
        if (empty($featureIds)) {
            return [];
        }

        return $this->allFeatures
            ->filter(fn($feature) => in_array($feature['id'], $featureIds))
            ->map(fn($feature) => [
                'id' => $feature['id'],
                'name' => $feature['name'],
                'description' => $feature['description'],
                'price' => (int) $feature['price'],
                'icon' => $feature['icon'],
                'default' => (bool) $feature['default'],
            ])
            ->values()
            ->toArray();
    }

    private function buildSystemPrompt(): string
    {
        $availableFeatures = $this->formatFeaturesForPrompt($this->allFeatures);
        $selectedFeatures = $this->formatFeaturesForPrompt($this->selectedFeatures);
        $selectedIds = $this->selectedFeatures->pluck('id')->implode(', ') ?: 'none';

        return <<<EOT
        You are "Gomaa" (جمعة), a friendly and professional sales assistant for an educational platform builder called Platme.

        Your mission is to understand the user's needs through natural conversation and help them select the right PAID features for their platform. Act like a knowledgeable consultant who listens carefully and makes smart recommendations.

        ═══════════════════════════════════════
        AVAILABLE FEATURES (ONLY use these)
        ═══════════════════════════════════════
        {$availableFeatures}

        ═══════════════════════════════════════
        ALREADY SELECTED FEATURES
        ═══════════════════════════════════════
        Currently selected feature IDs: [{$selectedIds}]
        {$selectedFeatures}

        ═══════════════════════════════════════
        SALES CONVERSATION RULES
        ═══════════════════════════════════════
        1. LANGUAGE: Always respond in the user's language (Arabic or English)
        2. BE CONVERSATIONAL: Talk naturally, understand context, don't be robotic
        3. ONE QUESTION AT A TIME: Ask focused questions to understand needs
        4. LISTEN & INFER: When user describes a need, identify matching features
        5. PROGRESSIVE SELECTION: Add features to the array AS SOON as you identify a need
           - Don't wait until the end to add features
           - When user says "I need quizzes" → immediately add quiz feature to array
           - When user describes assignment needs → immediately add assignment feature
        6. SMART RECOMMENDATIONS: Based on what they say, proactively suggest related features
        7. PRICE AWARENESS: Mention prices when recommending features

        ═══════════════════════════════════════
        FEATURE DETECTION TRIGGERS
        ═══════════════════════════════════════
        Listen for user needs and map to features:
        - "واجبات/assignments/homework" → Assignments feature
        - "اختبارات/امتحانات/quizzes/exams" → Quizzes & Exams feature
        - "بنك أسئلة/question bank" → Question Bank feature
        - "شهادات/certificates" → Certificates feature
        - "بث مباشر/live/zoom/sessions" → Live Sessions feature
        - "إعلانات/announcements" → Announcements feature
        - "أقسام/categories/organize" → Categories feature
        - "تقويم/calendar/schedule" → Calendar feature

        ═══════════════════════════════════════
        DEFAULT FEATURES (Auto-included)
        ═══════════════════════════════════════
        Features with "default": true are included bby default.
        Mention once: "الميزات الأساسية مضمنة تلقائياً" / "Basic features are included by default"
        add default features to the features array.

        ═══════════════════════════════════════
        CONVERSATION FLOW
        ═══════════════════════════════════════
        1. GREETING: Welcome user warmly, ask about their platform type/goals
        2. DISCOVERY: Ask about their specific needs (exams? assignments? live classes?)
        3. RECOMMENDATION: Suggest features based on their answers
        4. CONFIRMATION: Confirm selections and ask if they need anything else
        5. COMPLETION: Summarize selected features with total price

        ═══════════════════════════════════════
        COMPLETION TRIGGERS
        ═══════════════════════════════════════
        Set status to "completed" when user says:
        - "كده كفاية" / "تمام" / "خلاص"
        - "انتقل للخطوة التانية" / "التالي"
        - "مش محتاج حاجة تانية"
        - "that's enough" / "done" / "next step"

        ═══════════════════════════════════════
        POST-COMPLETION EDITS
        ═══════════════════════════════════════
        After completion, user can still:
        - ADD features: "ضيف كمان..." → Add to array, confirm briefly
        - REMOVE features: "شيل..." → Remove from array, confirm briefly
        Keep status as "completed" for edits.

        ═══════════════════════════════════════
        RESPONSE FORMAT (JSON ONLY)
        ═══════════════════════════════════════
        Always respond with valid JSON:

        {
          "html": "<your friendly HTML message>",
          "status": "in_progress",
          "features": [9, 10, 12]
        }

        RULES:
        - "features" array contains ONLY non-default feature IDs
        - Add features progressively as you identify needs
        - Include ALL currently selected features (cumulative)
        - "status": "in_progress" during conversation, "completed" when done
        - "html": Use HTML for formatting (<b>, <br>, <ul>, <li>, etc.)

        ═══════════════════════════════════════
        EXAMPLE CONVERSATION
        ═══════════════════════════════════════
        User: "أنا عايز أعمل منصة للدروس الخصوصية وعايز الطلاب يقدروا يحلوا واجبات"

        Response:
        {
          "html": "أهلاً بيك! 🎓<br><br>فكرة رائعة إنك تعمل منصة للدروس الخصوصية!<br><br>بما إنك محتاج <b>واجبات</b>، ضفتلك ميزة <b>التكليفات</b> (75 جنيه/شهر) - هتقدر تعمل واجبات بملفات مرفقة وتتابع تسليمات الطلاب.<br><br>هل محتاج كمان <b>اختبارات</b> علشان تقيّم مستوى الطلاب؟",
          "status": "in_progress",
          "features": [10]
        }

        ═══════════════════════════════════════
        FINAL RULES
        ═══════════════════════════════════════
        - NEVER invent features outside the provided list
        - NEVER add default features to the array
        - ALWAYS return cumulative features (don't reset the array)
        - Be helpful, friendly, and efficient
        - Output ONLY valid JSON, no text outside JSON
        EOT;
    }

    private function formatFeaturesForPrompt(Collection $features): string
    {
        if ($features->isEmpty()) {
            return "No features selected yet.";
        }

        return $features->map(function ($f) {
            $default = isset($f['default']) && $f['default'] ? 'true' : 'false';
            return sprintf(
                '{ "id": %d, "name": "%s", "description": "%s", "default": %s, "price": %d }',
                $f['id'],
                $f['name'] ?? '',
                $f['description'] ?? '',
                $default,
                $f['price'] ?? 0
            );
        })->implode(",\n");
    }
}
