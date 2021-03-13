<?php

namespace Database\Factories;

use App\Models\Profile;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

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
        $birthday = $this->faker->dateTimeBetween('-50 years', '-25 years');
        $age = Carbon::parse($birthday)->diffInYears();
        $phone = trim($this->faker->phoneNumber);

        $gender = $this->faker->randomElement(['male', 'female']);

        return [
            'lastname' => $this->faker->lastName($gender),
            'firstname' => $this->faker->firstName($gender),
            'patronymic' => $this->faker->middleName($gender),
            'birthday' => $birthday,
            'age' => $age,
            'gender' => $gender == 'male' ? 'м' : 'ж',
            'phone' => $phone,
        ];
    }
}
