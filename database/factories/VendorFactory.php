<?php
namespace Database\Factories;

use App\Models\Vendor;
use Illuminate\Database\Eloquent\Factories\Factory;

class VendorFactory extends Factory
{
    protected $model = Vendor::class;

    public function definition()
    {
        return [
            'name' => [
                'en' => $this->faker->company,
                'ar' => $this->faker->company,
                'tr' => $this->faker->company,
            ],
            'fullname' => $this->faker->name,
            'phone' => $this->faker->phoneNumber,
            'website' => $this->faker->url,
            'speciality' => $this->faker->word,
            'origin' => $this->faker->country,
            'address' => $this->faker->address,
            'note' => $this->faker->sentence,
            'logo' => $this->faker->imageUrl(100, 100, 'business', true, 'vendor'),
        ];
    }
}

