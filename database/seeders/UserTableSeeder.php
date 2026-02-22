<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert(
            [
                [
                    'name' => 'Admin',
                    'email' => 'admin@example.com',
                    'password' => Hash::make('password'),
                    'photo' => null,
                    'phone' => '1234567890',
                    'address' => '123 Admin Street',
                    'role' => 'admin',
                    'status' => '1',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'name' => 'User',
                    'email' => 'user@example.com',
                    'password' => Hash::make('password'),
                    'photo' => null,
                    'phone' => '1234567890',
                    'address' => '123 User Street',
                    'role' => 'user',
                    'status' => '1',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'name' => 'Instructor',
                    'email' => 'instructor@example.com',
                    'password' => Hash::make('password'),
                    'photo' => null,
                    'phone' => '1234567890',
                    'address' => '123 Instructor Street',
                    'role' => 'instructor',
                    'status' => '1',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            ]
        );
    }
}
