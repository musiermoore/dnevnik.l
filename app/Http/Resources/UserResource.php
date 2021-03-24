<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            'user_id'   => $this->id,
            'profile'   => ProfileResource::make($this->profile),
            'login'     => $this->login,
            'email'     => $this->email,
            'roles'     => $this->getRoleNames(),
            'group'     => GroupResource::collection($this->group),
        ];
    }
}
