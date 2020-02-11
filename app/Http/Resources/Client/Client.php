<?php

namespace App\Http\Resources\Client;

use Illuminate\Http\Resources\Json\JsonResource;

class Client extends JsonResource
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
            'name' => $this->name,
            'clientId' => $this->clientId,
            'userId' => $this->id,
            'clientName' => $this->clientName,
            'clientEmail' => $this->clientEmail,
            'email' => $this->email,
            'telephone' => $this->telephone,
            'location' => $this->location,
        ];
    }
}
