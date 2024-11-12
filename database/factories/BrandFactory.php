<?php

namespace Database\Factories;

use App\Models\Brand;
use Illuminate\Database\Eloquent\Factories\Factory;

class BrandFactory extends Factory
{
    protected $model = Brand::class;

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
