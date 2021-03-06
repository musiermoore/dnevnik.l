<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TimetableResource extends JsonResource
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
            'id'            => $this->id,
            'subject'       => $this->subject->subject,
            'classroom'     => $this->classroom->name,
            'lesson_number' => [
                'number' => $this->lessonNumber->number,
                'start'  => $this->lessonNumber->start_time,
                'end'    => $this->lessonNumber->end_time,
            ],
            'group'         => $this->group,
            'teacher'       => UserResource::make($this->teacher),
            'weekday'       => [
                'weekday_id'    => $this->weekday->id,
                'weekday_name'  => $this->weekday->day,
            ],
            'date'          => $this->date,
        ];
    }
}
