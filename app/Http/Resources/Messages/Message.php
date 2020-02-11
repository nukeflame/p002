<?php

namespace App\Http\Resources\Messages;

use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

class Message extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'senderId' => $this->sender_id,
            'chatId' => $this->chat_id,
            // 'receiverId' => $this->receiver_id,
            'message' => $this->message,
            'readAt' => Carbon::parse($this->seen_at)->format('Y-m-d'),
            'sender' => $this->sender,
            'chat' => $this->chat,
            'createdAt' => Carbon::parse($this->created_at)->format('Y-m-d'),
        ];
    }
}
