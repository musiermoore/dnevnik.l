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
        'curator',
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
     * Get group by name
     */
    public static function getGroupByName($groupName)
    {
        $group = Group::where('name', 'like', '%' . $groupName . '%')->first();

        return $group;
    }
}
