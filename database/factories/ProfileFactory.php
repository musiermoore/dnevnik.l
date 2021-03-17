<?php

namespace Database\Factories;

use App\Models\Profile;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ProfileFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Profile::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $gender = $this->faker->randomElement(['male', 'female']);

        $lastname   = $this->faker->lastName($gender);
        $firstname  = $this->faker->firstName($gender);
        $patronymic = $this->faker->middleName($gender);

        $birthday = $this->faker->dateTimeBetween('-50 years', '-25 years');
        $yearBirthday = \Carbon\Carbon::parse($birthday)->format('Y');

        $age = Carbon::parse($birthday)->diffInYears();
        $phone = trim($this->faker->phoneNumber);

        $login = Str::slug(Str::lower($lastname . $firstname . $yearBirthday));

        $user = \App\Models\User::create([
            'login'     => $login,
            'email'     => $login . "@example.com",
            'password'  => \Illuminate\Support\Str::random(8),
        ]);

        return [
            'user_id'       => $user->id,
            'lastname'      => $lastname,
            'firstname'     => $firstname,
            'patronymic'    => $patronymic,
            'birthday'      => $birthday,
            'age'           => $age,
            'gender'        => $gender == 'male' ? '0' : '1',
            'phone'         => $phone,
        ];
    }
}
