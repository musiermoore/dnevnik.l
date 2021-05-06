<?php

namespace App\Models;

use App\Http\Resources\UserResource;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasRoles, HasFactory, Notifiable;

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */

    protected $guard_name = 'api';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'profile_id',
        'login',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function profile()
    {
        return $this->hasOne(Profile::class);
    }

    public function group()
    {
        return $this->belongsToMany(Group::class, 'group_user', 'user_id');
    }

    public static function getUsersByRole($roles, $groupId = null)
    {
        $users = User::role($roles);

        if (! empty($groupId) && $roles == 3) {
            $users = self::getUsersByGroup($users, $groupId);
        }

        return $users->get();
    }

    private static function getUsersByGroup($users, $groupId)
    {
        $usersId = \Illuminate\Support\Facades\DB::table('group_user')
            ->whereIn('user_id', $users->pluck('id'))
            ->where('group_id', $groupId)
            ->pluck('user_id')
            ->toArray();

        return $users->whereIn('id', $usersId);
    }
}
