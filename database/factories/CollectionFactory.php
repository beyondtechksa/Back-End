<?php

namespace Database\Factories;

use App\Models\Collection;
use Illuminate\Database\Eloquent\Factories\Factory;

class CollectionFactory extends Factory
{
    protected $model = Collection::class;

    public function definition()
    {
        return [
            'name' => [
                'en' => $this->faker->word,
                'ar' => $this->faker->word,
                'tr' => $this->faker->word,
            ],
            'slug' => [
                'en' => $this->faker->slug,
                'ar' => $this->faker->slug,
                'tr' => $this->faker->slug,
            ],
            'image' => $this->faker->imageUrl(),
            'status' => $this->faker->boolean,
        ];
    }
}
