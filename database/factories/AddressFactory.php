<?php

namespace Database\Factories;

use App\Models\Address;
use Illuminate\Database\Eloquent\Factories\Factory;

class AddressFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Address::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'first_name' => $this->faker->firstName,
            'last_name' => $this->faker->lastName,
            'company_name' => $this->faker->company,
            'type' => $this->faker->randomElement(['home', 'work', 'other']),
            'details' => $this->faker->streetAddress,
            'user_id' => null, // You can modify this if you want to assign a user ID
            'favourite' => $this->faker->boolean(20), // 20% chance of being favourite
            'postal_code' => $this->faker->postcode,
            'street' => $this->faker->streetName,
            'city' => $this->faker->city,
            'country' => $this->faker->country,
        ];
    }
}
