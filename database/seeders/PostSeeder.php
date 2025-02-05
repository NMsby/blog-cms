<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Post;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear existing posts
        Post::query()->delete();

        // Get all categories and tags
        $categories = Category::all();
        $tags = Tag::all();
        $authors = User::where('role', 'author')->get();

        // Sample post data with different content types
        $postTemplates = [
            // Technology Posts
            [
                'title' => 'The Rise of Artificial Intelligence in Everyday Life',
                'excerpt' => 'Exploring how AI is transforming various industries and our daily experiences.',
                'categories' => ['Technology', 'Artificial Intelligence'],
                'tags' => ['Machine Learning', 'Emerging Tech', 'Innovation'],
                'status' => 'published',
                'is_featured' => true
            ],
            [
                'title' => 'Blockchain: Beyond Cryptocurrency',
                'excerpt' => 'Understanding the potential of blockchain technology in different sectors.',
                'categories' => ['Technology'],
                'tags' => ['Blockchain', 'Technology', 'Innovation'],
                'status' => 'published',
                'is_featured' => false
            ],
            // Lifestyle Posts
            [
                'title' => 'Mindfulness Techniques for Modern Professionals',
                'excerpt' => 'Practical strategies to manage stress and improve mental well-being in a fast-paced world.',
                'categories' => ['Lifestyle', 'Health & Wellness'],
                'tags' => ['Mindfulness', 'Personal Growth', 'Wellness'],
                'status' => 'published',
                'is_featured' => true
            ],
            [
                'title' => 'Budget Travel: Exploring the World on a Shoestring',
                'excerpt' => 'Tips and tricks for affordable and memorable travel experiences.',
                'categories' => ['Lifestyle', 'Travel'],
                'tags' => ['Travel Tips', 'Personal Growth'],
                'status' => 'published',
                'is_featured' => false
            ],
            // Culture Posts
            [
                'title' => 'The Evolution of Cinema in the Streaming Era',
                'excerpt' => 'How streaming platforms are changing the landscape of film production and consumption.',
                'categories' => ['Culture', 'Movies'],
                'tags' => ['Film Reviews', 'Entertainment', 'Pop Culture'],
                'status' => 'published',
                'is_featured' => true
            ],
            [
                'title' => 'Indie Music: Discovering Hidden Gems',
                'excerpt' => 'A deep dive into the vibrant world of independent music artists.',
                'categories' => ['Culture', 'Music'],
                'tags' => ['Music Trends', 'Art', 'Culture'],
                'status' => 'published',
                'is_featured' => false
            ],
            // Draft Posts
            [
                'title' => 'Upcoming Technology Trends in 2024',
                'excerpt' => 'Sneak peek into the technological innovations on the horizon.',
                'categories' => ['Technology'],
                'tags' => ['Emerging Tech', 'Innovation'],
                'status' => 'draft',
                'is_featured' => false
            ]
        ];

        // Create posts
        foreach ($postTemplates as $postTemplate) {
            // Select a random author
            $author = $authors->random();

            // Find matching categories
            $postCategories = Category::whereIn('name', $postTemplate['categories'])->get();

            // Find matching tags
            $postTags = Tag::whereIn('name', $postTemplate['tags'])->get();

            // Create the post
            $post = Post::create([
                'user_id' => $author->id,
                'title' => $postTemplate['title'],
                'slug' => Str::slug($postTemplate['title']),
                'excerpt' => $postTemplate['excerpt'],
                'content' => $this->generatePostContent($postTemplate['title']),
                'status' => $postTemplate['status'],
                'is_featured' => $postTemplate['is_featured'],
                'comments_enabled' => true,
                'published_at' => $postTemplate['status'] === 'published' ? now() : null,
                'meta_title' => $postTemplate['title'],
                'meta_description' => $postTemplate['excerpt']
            ]);

            // Attach categories and tags
            $post->categories()->attach($postCategories);
            $post->tags()->attach($postTags);
        }

        // Create some additional random posts using factory
        Post::factory()->count(20)->create();
    }

    /**
     * Generate sample post content
     */
    private function generatePostContent(string $title): string
    {
        return implode("\n\n", [
            "<h2>Introduction</h2>",
            "In today's rapidly changing world, " . Str::lower($title) . " has become an increasingly important topic.",
            "<h2>Key Insights</h2>",
            "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam auctor, nunc eget ultricies tincidunt, velit velit bibendum velit, vel bibendum velit velit sit amet velit.",
            "Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.",
            "<h2>Conclusion</h2>",
            "As we look to the future, it's clear that this topic will continue to evolve and shape our understanding of the world around us.",
        ]);
    }
}
