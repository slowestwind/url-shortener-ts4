<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            [
                'name' => 'admin',
                'description' => 'Administrator with full system access',
                'permissions' => json_encode([
                    'manage_users',
                    'manage_workspaces',
                    'view_analytics',
                    'manage_roles',
                    'manage_links',
                    'view_reports',
                ]),
            ],
            [
                'name' => 'customer',
                'description' => 'Regular user who can create and manage links',
                'permissions' => json_encode([
                    'create_links',
                    'edit_own_links',
                    'delete_own_links',
                    'view_own_analytics',
                    'download_qr',
                    'customize_profile',
                ]),
            ],
            [
                'name' => 'guest',
                'description' => 'Limited access user, can only view public profiles',
                'permissions' => json_encode([
                    'view_public_profiles',
                    'view_public_links',
                ]),
            ],
        ];

        foreach ($roles as $role) {
            Role::updateOrCreate(
                ['name' => $role['name']],
                $role
            );
        }
    }
}
