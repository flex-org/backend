<?php
namespace App\Modules\AIChatBot\Controllers;

use App\Facades\ApiResponse;
use App\Http\Controllers\Controller;
use App\Modules\AIChatBot\Services\AIChatBotService;
use App\Modules\AIChatBot\Requests\SendMessageRequest;
use Illuminate\Support\Facades\Auth;

class AIChatBotController extends Controller
{
    private $chatbot;

    public function __construct(AIChatBotService $aichatbot)
    {
        $this->chatbot = $aichatbot;
    }

    public function chat(SendMessageRequest $request)
    {
        $data = $request->validated();
        $userId = Auth::id();
        $reply = $this->chatbot->sendMessage($data['message'], $userId) ;
        return ApiResponse::success([
            'user' => $data['message'],
            'bot' => $reply->html,
            'status' => $reply->status ?? 'in_progress',
            'features' => $reply->features ?? []
        ]);
    }

}
