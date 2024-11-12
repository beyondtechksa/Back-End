<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $name = $this->faker->name;
        $email = $this->faker->unique()->safeEmail;
        $phone = $this->faker->phoneNumber;
        $password = Hash::make('password'); // Or use bcrypt('password')
        $avatar = $this->faker->imageUrl(300, 300, 'people', true);
        $providerId = $this->faker->uuid;
        $provider = $this->faker->randomElement(['google', 'facebook', 'twitter']);
        $uuid = $this->faker->uuid;
        $emailVerifiedAt = $this->faker->dateTimeBetween('-2 years', 'now');
        $rememberToken = Str::random(10);
        $createdAt = $this->faker->dateTimeBetween('-2 years', 'now');
        $updatedAt = $this->faker->dateTimeBetween($createdAt, 'now');

        return [
            'name' => $name,
            'email' => $email,
            'phone' => $phone,
            'password' => $password,
            'avatar' => $avatar,
            'provider_id' => $providerId,
            'provider' => $provider,
            'uuid' => $uuid,
            'email_verified_at' => $emailVerifiedAt,
            'remember_token' => $rememberToken,
            'created_at' => $createdAt,
            'updated_at' => $updatedAt,
            'gender' => 'female'
        ];
    }
}
