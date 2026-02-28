<?php

namespace App\Modules\V1\AIChatBot\Controllers;

use App\Facades\ApiResponse;
use App\Modules\V1\AIChatBot\Requests\SendMessageRequest;
use App\Modules\V1\AIChatBot\Services\AIChatBotService;
use Illuminate\Support\Facades\Auth;

class AIChatBotController
{
    public function __construct(private AIChatBotService $chatbot) {}

    public function chat(SendMessageRequest $request)
    {
        $data = $request->validated();

        $reply = $this->chatbot->sendMessage(
            $data['message'],
            Auth::id(),
        );
//        dd($reply);
        return ApiResponse::success(
            [
                'user' => $data['message'],
                'bot' => $reply->html,
                'status' => $reply->status,
                'features' => $reply->features,
            ]
        );
    }
}
