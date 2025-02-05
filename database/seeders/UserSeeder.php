<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear existing users (except the one from AdminUserSeeder)
        User::where('email', '!=', 'admin@example.com')->delete();

        // Create additional admin users
        $admins = [
            [
                'name' => 'System Administrator',
                'email' => 'sysadmin@example.com',
                'username' => 'sysadmin',
                'role' => 'admin',
                'bio' => 'Responsible for overall system management and configuration.',
                'website' => 'https://example.com/sysadmin',
            ]
        ];

        // Create editor users
        $editors = [
            [
                'name' => 'Emma Johnson',
                'email' => 'emma.editor@example.com',
                'username' => 'emmaj',
                'role' => 'editor',
                'bio' => 'Experienced editor with a passion for technology and innovation.',
                'website' => 'https://techinsights.blog',
            ],
            [
                'name' => 'Michael Chen',
                'email' => 'michael.editor@example.com',
                'username' => 'michaelc',
                'role' => 'editor',
                'bio' => 'Culture enthusiast and content strategist.',
                'website' => 'https://cultureedge.com',
            ]
        ];

        // Create author users
        $authors = [
            [
                'name' => 'Sarah Thompson',
                'email' => 'sarah.author@example.com',
                'username' => 'saraht',
                'role' => 'author',
                'bio' => 'Technology writer and AI enthusiast.',
                'website' => 'https://techwriter.com',
            ],
            [
                'name' => 'David Rodriguez',
                'email' => 'david.author@example.com',
                'username' => 'davidr',
                'role' => 'author',
                'bio' => 'Lifestyle blogger focusing on wellness and personal growth.',
                'website' => 'https://lifestyleinsights.blog',
            ],
            [
                'name' => 'Alex Kim',
                'email' => 'alex.author@example.com',
                'username' => 'alexk',
                'role' => 'author',
                'bio' => 'Culture critic and entertainment journalist.',
                'website' => 'https://culturereview.com',
            ]
        ];

        // Helper function to create users
        $createUsers = function($users) {
            foreach ($users as $userData) {
                User::create([
                    'name' => $userData['name'],
                    'email' => $userData['email'],
                    'username' => $userData['username'],
                    'email_verified_at' => now(),
                    'password' => Hash::make('password'),
                    'role' => $userData['role'],
                    'bio' => $userData['bio'],
                    'website' => $userData['website'],
                    'social_links' => [
                        'twitter' => 'https://twitter.com/' . $userData['username'],
                        'linkedin' => 'https://linkedin.com/in/' . $userData['username']
                    ]
                ]);
            }
        };

        // Create users
        $createUsers($admins);
        $createUsers($editors);
        $createUsers($authors);

        // Optional: Create additional random authors using factory
        User::factory()->count(5)->create([
            'role' => 'author',
            'email_verified_at' => now(),
        ]);
    }
}
