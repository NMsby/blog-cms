<?php

namespace Database\Factories;

use App\Models\Media;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class MediaFactory extends Factory
{
    protected $model = Media::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'filename' => fake()->uuid() . '.jpg',
            'original_filename' => fake()->word() . '.jpg',
            'mime_type' => 'image/jpeg',
            'size' => fake()->numberBetween(100000, 5000000),
            'path' => 'media/' . fake()->uuid() . '.jpg',
            'additional_attributes' => [
                'dimensions' => [
                    'width' => 1920,
                    'height' => 1080
                ]
            ],
        ];
    }
}
