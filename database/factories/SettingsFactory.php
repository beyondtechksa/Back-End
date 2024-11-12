<?php

namespace Database\Factories;

use App\Models\Settings;
use Illuminate\Database\Eloquent\Factories\Factory;

class SettingsFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Settings::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $slugs = ['single_banner', 'single_banner2', 'single_banner3', 'single_banner4', 'single_banner5'];

        return [
            'name' => $this->faker->word,
            'slug' =>  $this->faker->randomElement($slugs),
            'key' => ['en' => $this->faker->word, 'ar' => $this->faker->word], // Assuming 'key' is translatable
            'value' => ['en' => $this->faker->sentence, 'ar' => $this->faker->sentence],
            'link' => $this->faker->url,
            'type' => $this->faker->randomElement(['type1', 'type2', 'type3']),
            'status' => $this->faker->boolean,
        ];
    }
}
