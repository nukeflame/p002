<?php

namespace App\Http\Resources\User;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Role\RoleCollection;
use App\Http\Resources\Notification\NotificationCollection;
use App\Client;
use App\Http\Resources\Client\Client as ClientResource;

class User extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        // $client = Client::where('id', $this->staff->client_id)->first();
        return [
            'id' => $this->id,
            'name' => $this->name,
            'notifications' => new NotificationCollection($this->notifications),
            'unreadNotf' => new NotificationCollection($this->unreadNotifications),
            'email' => $this->email,
            'roles' => new RoleCollection($this->roles),
            // 'client' => new ClientResource($client),
            'isActive' => $this->is_active
        ];
    }
}
