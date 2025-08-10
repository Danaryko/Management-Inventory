<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            [
                'name' => 'Admin',
                'email' => 'admin@gmail.com',
                'password' => Hash::make('password'),
                'roles' => 'admin',
            ],
            [
                'name' => 'Owner',
                'email' => 'owner@gmail.com',
                'password' => Hash::make('password'),
                'roles' => 'owner',
            ],
            [
                'name' => 'Operator Satu',
                'email' => 'operator@gmail.com',
                'password' => Hash::make('password'),
                'roles' => 'operator',
            ],
            [
                'name' => 'Operator Dua',
                'email' => 'operator2@gmail.com',
                'password' => Hash::make('password'),
                'roles' => 'operator',
            ],
        ];

        foreach ($users as $user) {
            User::create($user);
        }

        // Seeder lainnya
        $this->call([
            CategorySeeder::class,
        ]);
    }
}
