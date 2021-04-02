<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Timetable extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'subject_id',
        'classroom_id',
        'lesson_numbers_id',
        'group_id',
        'teacher_id',
        'weekday_id',
        'date',
    ];

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

    public function getDateAttribute($date)
    {
        return \Carbon\Carbon::createFromFormat('Y-m-d', $date)->format('d.m.Y');
    }

    /**
     * @param $teacherId
     * @return mixed
     */
    public static function getWeekTimetableForTeacher($teacherId)
    {
        return Timetable::where('teacher_id', $teacherId);
    }

    /**
     * @param $groupId
     * @return mixed
     */
    public static function getWeekTimetableForStudent($groupId)
    {
        return Timetable::where('group_id', $groupId);
    }

    /**
     * @param $timetable
     * @param $weekStartDate
     * @param $weekEndDate
     * @return mixed
     */
    public static function getWeekTimetableBetweenTwoDates($timetable, $weekStartDate, $weekEndDate)
    {
        return $timetable->whereBetween('date', [
            $weekStartDate,
            $weekEndDate
        ])->orderBy('date')
            ->orderBy('lesson_numbers_id')
            ->get();
    }

    public static function checkAvailabilityTeacher($lessonData)
    {
        return Timetable::where('date', $lessonData['date'])
            ->where('lesson_numbers_id', $lessonData['lesson_number'])
            ->where('teacher_id', $lessonData['teacher'])
            ->get();
    }

    public static function checkAvailabilityGroup($lessonData)
    {
        return Timetable::where('date', $lessonData['date'])
            ->where('lesson_numbers_id', $lessonData['lesson_number'])
            ->where('group_id', $lessonData['group'])
            ->get();
    }

    public static function checkAvailabilityClassroom($lessonData)
    {
        return Timetable::where('date', $lessonData['date'])
            ->where('lesson_numbers_id', $lessonData['lesson_number'])
            ->where('classroom_id', $lessonData['classroom'])
            ->get();
    }
}
