<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class GroupResource extends JsonResource
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
            'group'         => $this->name,
            'curator'       => UserResource::make($this->curator),
            'students'      => UserResource::collection($this->students),
        ];
    }
}
