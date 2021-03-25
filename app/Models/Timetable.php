<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Timetable extends Model
{
    use HasFactory;

    public $timestamps = false;

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function weekday()
    {
        return $this->belongsTo(Weekday::class);
    }

    public function lessonNumber()
    {
        return $this->belongsTo(LessonNumber::class, 'lesson_numbers_id', 'id');
    }

    public function classroom()
    {
        return $this->belongsTo(Classroom::class);
    }

    public function group()
    {
        return $this->belongsTo(Group::class);
    }

    public function teacher()
    {
        return $this->belongsTo(User::class);
    }

    public static function getWeekTimetableForTeacher($teacherId)
    {
        $timetable = Timetable::where('teacher_id', $teacherId);

        return $timetable;
    }

    public static function getWeekTimetableForStudent($groupId)
    {
        $timetable = Timetable::where('group_id', $groupId);

        return $timetable;
    }

    public static function getWeekTimetableBetweenTwoDates($timetable, $weekStartDate, $weekEndDate)
    {
        $result = $timetable->whereBetween('date', [
            $weekStartDate,
            $weekEndDate
        ])->orderBy('date')
            ->orderBy('lesson_numbers_id')
            ->get();

        return $result;
    }
}
