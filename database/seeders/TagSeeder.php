<?php

namespace Database\Seeders;

use App\Models\Tag;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class TagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear existing tags
        Tag::query()->delete();

        // Define tag groups
        $tagGroups = [
            'Technology' => [
                'Machine Learning',
                'Blockchain',
                'Cybersecurity',
                'Cloud Computing',
                'Emerging Tech',
                'Open Source',
                'Programming'
            ],
            'Lifestyle' => [
                'Wellness',
                'Fitness',
                'Mindfulness',
                'Travel Tips',
                'Personal Growth',
                'Nutrition',
                'Work-Life Balance'
            ],
            'Culture' => [
                'Film Reviews',
                'Music Trends',
                'Book Recommendations',
                'Art',
                'Entertainment',
                'Pop Culture',
                'Media Criticism'
            ],
            'General' => [
                'Innovation',
                'Sustainability',
                'Community',
                'Education',
                'Technology',
                'Success',
                'Inspiration'
            ]
        ];

        // Create tags
        foreach ($tagGroups as $group => $tags) {
            foreach ($tags as $tagName) {
                Tag::create([
                    'name' => $tagName,
                    'slug' => Str::slug($tagName),
                    'description' => "Tags related to $group category: " . $tagName
                ]);
            }
        }
    }
}
