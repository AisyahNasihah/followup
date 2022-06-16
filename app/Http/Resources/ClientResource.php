<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ClientResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $category = $this->whenLoaded(relationship: 'category');
        return [
            'id' => $this->id,
            'company' => $this->company,
            'name' => $this->name,
            'phone_number' => $this->phone_number,
            'email' => $this->email,
            'active' => $this->active,
            'category' => new CategoryResource($category),
            
        ];
    }
}
