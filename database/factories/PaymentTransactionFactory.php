<?php

namespace Database\Factories;

use App\Models\PaymentTransaction;
use Illuminate\Database\Eloquent\Factories\Factory;

class PaymentTransactionFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = PaymentTransaction::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => function () {
                return \App\Models\User::factory()->create()->id;
            },
            'address_id' => function () {
                return \App\Models\Address::factory()->create()->id;
            },
            'total' => $this->faker->randomFloat(2, 10, 1000),
            'uuid' => $this->faker->uuid,
            'response' => $this->faker->text,
            'status' => $this->faker->boolean(80) ? 0 : 1, // 80% chance of status being 0
        ];
    }
}
