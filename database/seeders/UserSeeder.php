<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Admin user
        User::updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name'     => 'Admin',
                'password' => bcrypt('admin123'),
                'role'     => 'admin',
                'balance'  => 100000000,
            ]
        );

        // Regular users
        $users = [
            ['name' => 'Budi Santoso', 'email' => 'budi@example.com'],
            ['name' => 'Siti Rahayu', 'email' => 'siti@example.com'],
            ['name' => 'Ahmad Fauzi', 'email' => 'ahmad@example.com'],
            ['name' => 'Dewi Lestari', 'email' => 'dewi@example.com'],
            ['name' => 'Rizky Pratama', 'email' => 'rizky@example.com'],
        ];

        foreach ($users as $user) {
            User::updateOrCreate(
                ['email' => $user['email']],
                [
                    'name'     => $user['name'],
                    'password' => bcrypt('password123'),
                    'role'     => 'user',
                    'balance'  => rand(5, 50) * 1000000,
                ]
            );
        }
    }
}