<?php
// database/seeders/DatabaseSeeder.php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create Super Admin
        User::create([
            'name' => 'Super Admin',
            'email' => 'superadmin@grievance.com',
            'password' => Hash::make('password'),
            'role' => 'super_admin',
        ]);

        // Create Regular Admin
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@grievance.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        // Create Categories
        $categories = [
            [
                'name' => 'Harassment',
                'description' => 'Workplace harassment and discrimination issues',
                'is_active' => true,
            ],
            [
                'name' => 'Financial Misconduct',
                'description' => 'Financial irregularities and fraud',
                'is_active' => true,
            ],
            [
                'name' => 'Safety Violation',
                'description' => 'Health and safety concerns',
                'is_active' => true,
            ],
            [
                'name' => 'Ethical Violation',
                'description' => 'Code of conduct and ethics violations',
                'is_active' => true,
            ],
            [
                'name' => 'Data Privacy',
                'description' => 'Data protection and privacy concerns',
                'is_active' => true,
            ],
            [
                'name' => 'Other',
                'description' => 'Other grievances not covered in above categories',
                'is_active' => true,
            ],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}