<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rate extends Model
{
    use HasFactory;

    public function student()
    {
        return $this->belongsTo(User::class);
    }

    public function lesson()
    {
        return $this->hasOne(Timetable::class, 'id','lesson_id');
    }

    public function group()
    {
        return $this->belongsTo(Group::class);
    }

    protected $fillable = [
        'id',
        'student_id',
        'lesson_id',
        'rate',
    ];
}
