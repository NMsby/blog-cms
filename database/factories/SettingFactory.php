<?php

namespace Database\Factories;

use App\Models\Setting;
use Illuminate\Database\Eloquent\Factories\Factory;

class SettingFactory extends Factory
{
    protected $model = Setting::class;

    public function definition(): array
    {
        return [
            'key' => $this->faker->unique()->word,
            'value' => $this->faker->text,
            'group' => $this->faker->randomElement(['general', 'site', 'social', 'seo']),
            'type' => 'text',
            'is_public' => true,
        ];
    }
}
