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
        // 1. Akun Admin
        User::updateOrCreate(
            ['email' => 'ben123@gmail.com'],
            [
                'name' => 'Benedictus',
                'password' => Hash::make('ben123'),
                'role' => 'admin',
            ]
        );

        // 2. Akun Customer
        User::updateOrCreate(
            ['email' => 'julius@gmail.com'],
            [
                'name' => 'Julius',
                'password' => Hash::make('julius123'),
                'role' => 'customer',
            ]
        );
    }
}