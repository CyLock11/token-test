<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('users')->truncate();

        DB::table('users')->insert([
            [
                'username' => 'admin',
                'email' => 'admin@example.com',
                'password' => Hash::make('1234567890'),
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'username' => 'cylock',
                'email' => 'cylock@gmail.com',
                'password' => Hash::make('mypassword1234'),
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'username' => 'jorge',
                'email' => 'jorge@gmail.com',
                'password' => Hash::make('soyelcerilla901'),
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);
    }
}
