<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

class CategoryFactory extends Factory
{
    protected $model = Category::class;

    public function definition()
    {
        return [
            'list' => $this->faker->numberBetween(1, 100),
            'name' => [
                'en' => $this->faker->word,
                'ar' => $this->faker->word,
                'tr' => $this->faker->word,
            ],
            'menu_name' => [
                'en' => $this->faker->word,
                'ar' => $this->faker->word,
                'tr' => $this->faker->word,
            ],
            'desc' => [
                'en' => $this->faker->sentence,
                'ar' => $this->faker->sentence,
                'tr' => $this->faker->sentence,
            ],
            'slug' => [
                'en' => $this->faker->slug,
                'ar' => $this->faker->slug,
                'tr' => $this->faker->slug,
            ],
            'category_id' => null, // Default to null for parent categories
            'image' => $this->faker->imageUrl(),
            'status' => $this->faker->boolean,
            //'show_in_navbar' => $this->faker->boolean,
            //'mark_as_top_category' => $this->faker->boolean,
        ];
    }
}
