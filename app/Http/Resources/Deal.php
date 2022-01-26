<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\User;

class Deal extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'user' => new User($this->user),
            'owner' => new User($this->owner),
            'bid' => json_decode($this->bid),
            'trades' => json_decode($this->trades),
            'created' => $this->created_at->format('d/m/Y h:i:s')
        ];
    }
}
