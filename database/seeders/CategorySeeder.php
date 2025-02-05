<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear existing categories
        Category::query()->delete();

        // Create parent categories
        $parentCategories = [
            [
                'name' => 'Technology',
                'description' => 'Latest news and insights in the world of technology',
                'children' => [
                    ['name' => 'Artificial Intelligence', 'description' => 'Exploring AI innovations and trends'],
                    ['name' => 'Web Development', 'description' => 'Coding, frameworks, and web technologies'],
                    ['name' => 'Gadgets', 'description' => 'Reviews and news about the latest tech gadgets']
                ]
            ],
            [
                'name' => 'Lifestyle',
                'description' => 'Lifestyle, health, and personal development content',
                'children' => [
                    ['name' => 'Travel', 'description' => 'Travel guides, tips, and personal experiences'],
                    ['name' => 'Health & Wellness', 'description' => 'Fitness, nutrition, and mental health'],
                    ['name' => 'Personal Finance', 'description' => 'Money management and financial advice']
                ]
            ],
            [
                'name' => 'Culture',
                'description' => 'Arts, entertainment, and cultural insights',
                'children' => [
                    ['name' => 'Movies', 'description' => 'Film reviews, industry news, and recommendations'],
                    ['name' => 'Music', 'description' => 'Latest music trends, album reviews, and artist profiles'],
                    ['name' => 'Books', 'description' => 'Book reviews, reading recommendations, and literary news']
                ]
            ]
        ];

        // Create parent and child categories
        foreach ($parentCategories as $parentData) {
            $children = $parentData['children'];
            unset($parentData['children']);

            // Create parent category
            $parent = Category::create([
                'name' => $parentData['name'],
                'slug' => Str::slug($parentData['name']),
                'description' => $parentData['description'],
                'order' => 0,
                'is_visible' => true
            ]);

            // Create child categories
            foreach ($children as $childData) {
                Category::create([
                    'name' => $childData['name'],
                    'slug' => Str::slug($childData['name']),
                    'description' => $childData['description'],
                    'parent_id' => $parent->id,
                    'order' => 0,
                    'is_visible' => true
                ]);
            }
        }
    }
}
