<?php

namespace Database\Factories;

use App\Models\Order;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Order::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => $this->faker->numberBetween(1, 10),
            'subtotal_amount' => $this->faker->randomFloat(2, 50, 500),
            'shipping' => $this->faker->numberBetween(5, 50),
            'discount' => $this->faker->numberBetween(0, 30),
            'total_amount' => function (array $attributes) {
                return $attributes['subtotal_amount'] + $attributes['shipping'] - $attributes['discount'];
            },
            'address' => [
                'id' => $this->faker->unique()->numberBetween(1, 100),
                'type' => 'bb',
                'first_name' => $this->faker->firstName,
                'last_name' => $this->faker->lastName,
                'details' => $this->faker->address,
                'user_id' => $this->faker->numberBetween(1, 10),
                'neighborhood' => null,
                'postal_code' => $this->faker->postcode,
                'street' => $this->faker->streetAddress,
                'building_name' => null,
                'city' => $this->faker->city,
                'country' => $this->faker->country,
                'favourite' => $this->faker->boolean(20),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            'status' => $this->faker->numberBetween(0, 5),
            'payment_id' => $this->faker->uuid,
        ];
    }
}
