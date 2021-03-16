<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProfileResource extends JsonResource
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
            'lastname'  => $this->lastname,
            'firstname'  => $this->firstname,
            'patronymic'  => $this->patronymic,
            'age'  => $this->age,
            'birthday'  => $this->birthday,
            'gender'  => $this->gender,
            'phone'  => $this->phone,
        ];
    }
}
