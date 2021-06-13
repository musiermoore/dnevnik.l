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
            'group_id'      => $this->id,
            'group_name'    => $this->name,
            'curator'       => UserResource::make($this->curator),
            'enrollment_date' => $this->enrollment_date,
            'graduation_date' => $this->graduation_date,
            'duration_study'  => $this->duration_study,
        ];
    }
}
