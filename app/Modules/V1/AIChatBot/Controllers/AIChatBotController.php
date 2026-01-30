<?php

namespace App\Modules\V1\AIChatBot\Controllers;

use App\Facades\ApiResponse;
use App\Http\Controllers\Controller;
use App\Modules\V1\AIChatBot\Services\AIChatBotService;
use App\Modules\V1\AIChatBot\Requests\SendMessageRequest;
use App\Modules\V1\AIChatBot\Resources\AIChatBotResource;
use App\Modules\V1\Features\Services\FeatureService;
use App\Modules\V1\Platforms\Services\InitializePlatformService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class AIChatBotController extends Controller
{
    public function __construct(
        private AIChatBotService $chatbot,
    ) {}

    public function chat(SendMessageRequest $request)
    {
        $data = $request->validated();
        $user = Auth::user();

        $reply = $this->chatbot->sendMessage(
            $data['message'],
            $user->id,
        );
        return ApiResponse::success(
            [
                'user_message' => $data['message'],
                'html' => $reply->html,
                'status' => $reply->status,
                'features' => $reply->features,
            ]
        );
    }
}
