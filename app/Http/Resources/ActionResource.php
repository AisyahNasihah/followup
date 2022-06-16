<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ActionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $type = $this->whenLoaded(relationship: 'type');
        $course = $this->whenLoaded(relationship: 'course');
        return [
            'id' => $this->id,
            'comment' => $this->comment,
            'when' => $this->when,
            'time' => $this->time,
            'type' => new TypeResource($type),
            'course' => new CourseResource($course),
            'active' => $this->active,
        ];
    }
}
