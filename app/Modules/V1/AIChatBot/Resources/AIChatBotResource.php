<?php

namespace App\Modules\V1\AIChatBot\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AIChatBotResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'user' => $this['user_message'],
            'bot' => $this['html'],
            'status' => $this['status'],
            'features' => $this['features'],
            'newly_added' => $this['newly_added'],
        ];
    }
}

