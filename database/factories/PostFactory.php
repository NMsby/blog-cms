<?php

namespace Database\Factories;

use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class PostFactory extends Factory
{
    protected $model = Post::class;

    public function definition(): array
    {
        $title = fake()->sentence();
        return [
            'user_id' => User::factory(),

            'title' => $title,
            'slug' => Str::slug($title),
            'excerpt' => fake()->paragraph(),
            'content' => fake()->paragraphs(5, true),
            'featured_image' => null,
            'meta_title' => $title,
            'meta_description' => fake()->sentence(),
            'status' => fake()->randomElement(['draft', 'published']),
            'is_featured' => fake()->boolean(),
            'published_at' => fake()->dateTimeBetween('-1 year'),
        ];
    }
}
