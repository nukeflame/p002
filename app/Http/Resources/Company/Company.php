<?php

namespace App\Http\Resources\Company;

use Illuminate\Http\Resources\Json\JsonResource;

class Company extends JsonResource
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
            'logoUrl' => $this->logo,
            'address' => $this->address,
            'regNo' => $this->reg_no,
            'vatNo' => $this->vat_no,
            'agentNo' => $this->agent_no,
            'website' => $this->website,
            'docId' => $this->doc_id,
            'pinNo' => $this->pin_no,
            'kraPin' => $this->kra_pin,
            'tel2' => $this->telephone_1,
            'tel1' => $this->telephone_2,
            'clientId' => $this->client_id,
            'clientCode' => $this->client->clientId,
        ];
    }
}
