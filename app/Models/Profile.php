<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    use HasFactory;

    public function users()
    {
        return $this->hasOne(User::class, 'profile_id', 'id');
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
}
