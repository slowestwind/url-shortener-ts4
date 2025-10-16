<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Workspace;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
        ]);

        // Create a test workspace
        $workspace = Workspace::create([
            'name' => 'Test Workspace',
            'slug' => 'test-workspace',
            'description' => 'Default workspace for testing',
            'is_active' => true,
        ]);

        // Create a test admin user
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@ts4.in',
            'password' => bcrypt('password'),
            'workspace_id' => $workspace->id,
            'role' => 'admin',
            'is_active' => true,
        ]);

        // Create admin profile
        $admin->profile()->create([
            'bio' => 'System Administrator',
            'profile_slug' => 'admin',
            'display_name' => 'Admin',
        ]);

        // Create a test customer user
        $customer = User::create([
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => bcrypt('password'),
            'workspace_id' => $workspace->id,
            'role' => 'customer',
            'is_active' => true,
        ]);

        // Create customer profile
        $customer->profile()->create([
            'bio' => 'Digital Creator and Tech Enthusiast',
            'profile_slug' => 'johndoe',
            'display_name' => 'John Doe',
            'website_url' => 'https://example.com',
            'social_links' => json_encode([
                'twitter' => 'https://twitter.com/johndoe',
                'instagram' => 'https://instagram.com/johndoe',
                'linkedin' => 'https://linkedin.com/in/johndoe',
            ]),
        ]);

        // Create test short links
        $links = [
            [
                'title' => 'My Portfolio',
                'target_url' => 'https://example.com/portfolio',
                'category' => 'professional',
            ],
            [
                'title' => 'Blog Post',
                'target_url' => 'https://example.com/blog/first-post',
                'category' => 'content',
            ],
            [
                'title' => 'GitHub Profile',
                'target_url' => 'https://github.com/johndoe',
                'category' => 'social',
            ],
        ];

        foreach ($links as $link) {
            $customer->shortLinks()->create([
                'slug' => str()->random(6),
                'target_url' => $link['target_url'],
                'title' => $link['title'],
                'category' => $link['category'],
                'is_active' => true,
            ]);
        }
    }
}
