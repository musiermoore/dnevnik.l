<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'name',
        'curator_id',
        'enrollment_date',
        'graduation_date',
        'duration_study'
    ];

    public function students()
    {
        return $this->belongsToMany(User::class);
    }

    public function curator()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get main group
     */
    public static function getMainGroup($group)
    {
        return $group[0];
    }

    /**
     * Get group by id
     */
    public static function getGroupById($groupId)
    {
        return Group::where('id', $groupId)->first();
    }
}
