<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear existing settings
        Setting::query()->delete();

        // Define settings groups
        $settingsGroups = [
            // Site Configuration
            'site' => [
                [
                    'key' => 'site_name',
                    'value' => 'Tech Insights Blog',
                    'type' => 'text',
                    'is_public' => true
                ],
                [
                    'key' => 'site_description',
                    'value' => 'Your go-to source for technology, lifestyle, and culture insights',
                    'type' => 'textarea',
                    'is_public' => true
                ],
                [
                    'key' => 'maintenance_mode',
                    'value' => '0',
                    'type' => 'boolean',
                    'is_public' => false
                ]
            ],
            // SEO Settings
            'seo' => [
                [
                    'key' => 'meta_title',
                    'value' => 'Tech Insights - Technology, Lifestyle & Culture Blog',
                    'type' => 'text',
                    'is_public' => true
                ],
                [
                    'key' => 'meta_description',
                    'value' => 'Explore cutting-edge technology, lifestyle trends, and cultural insights.',
                    'type' => 'textarea',
                    'is_public' => true
                ],
                [
                    'key' => 'google_analytics_id',
                    'value' => '',
                    'type' => 'text',
                    'is_public' => false
                ]
            ],
            // Social Media Settings
            'social' => [
                [
                    'key' => 'facebook_url',
                    'value' => 'https://facebook.com/techinsightsblog',
                    'type' => 'text',
                    'is_public' => true
                ],
                [
                    'key' => 'twitter_url',
                    'value' => 'https://twitter.com/techinsights',
                    'type' => 'text',
                    'is_public' => true
                ],
                [
                    'key' => 'instagram_url',
                    'value' => 'https://instagram.com/techinsightsblog',
                    'type' => 'text',
                    'is_public' => true
                ]
            ],
            // Comments Settings
            'comments' => [
                [
                    'key' => 'comments_enabled',
                    'value' => '1',
                    'type' => 'boolean',
                    'is_public' => true
                ],
                [
                    'key' => 'comments_moderation',
                    'value' => '1',
                    'type' => 'boolean',
                    'is_public' => false
                ],
                [
                    'key' => 'comments_require_login',
                    'value' => '0',
                    'type' => 'boolean',
                    'is_public' => true
                ]
            ],
            // Email Settings
            'email' => [
                [
                    'key' => 'admin_email',
                    'value' => 'admin@techinsightsblog.com',
                    'type' => 'text',
                    'is_public' => false
                ],
                [
                    'key' => 'email_notifications_enabled',
                    'value' => '1',
                    'type' => 'boolean',
                    'is_public' => false
                ]
            ],
            // Performance Settings
            'performance' => [
                [
                    'key' => 'posts_per_page',
                    'value' => '12',
                    'type' => 'text',
                    'is_public' => true
                ],
                [
                    'key' => 'cache_enabled',
                    'value' => '1',
                    'type' => 'boolean',
                    'is_public' => false
                ],
                [
                    'key' => 'cache_duration',
                    'value' => '60',
                    'type' => 'text',
                    'is_public' => false
                ]
            ]
        ];

        // Create settings
        foreach ($settingsGroups as $group => $settings) {
            foreach ($settings as $setting) {
                Setting::create([
                    'key' => $setting['key'],
                    'value' => $setting['value'],
                    'group' => $group,
                    'type' => $setting['type'],
                    'is_public' => $setting['is_public']
                ]);
            }
        }
    }
}
