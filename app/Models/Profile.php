<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    use HasFactory;

    public function users()
    {
        return $this->belongsTo(User::class, 'profile_id', 'id');
    }

    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'lastname',
        'firstname',
        'patronymic',
        'age',
        'birthday',
        'gender',
        'phone',
    ];

    /**
     * Get full gender name
     *
     * @param $gender
     * @return string
     */
    public function getGenderAttribute($gender)
    {
        if ($gender == 0) {
            $newGender = "Мужчина";
        } else {
            $newGender = "Женщина";
        }

        return $newGender;
    }
}
