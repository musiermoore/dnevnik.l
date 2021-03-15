<?php

namespace Database\Factories;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = User::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $profileIds = \App\Models\Profile::pluck('id')->toArray();
        $profileId = $this->faker->unique()->randomElement($profileIds);
        $profile = \App\Models\Profile::find($profileId);

        $date = new Carbon($profile->birthday);
        $login = Str::slug(Str::lower($profile->lastname . "." . $profile->firstname . $date->format('Y')));

        return [
            'profile_id' => $profileId,
            'login' => $login,
            'email' => $login . "@example.exp",
            'password' => Str::random(10),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function unverified()
    {
        return $this->state(function (array $attributes) {
            return [
                'email_verified_at' => null,
            ];
        });
    }
}
