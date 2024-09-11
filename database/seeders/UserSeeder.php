<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            [
                'name' => '管理者大川',
                'email' => 'admin@admin.com',
                'password' => Hash::make('password123'),
                'role' => 1
            ],
            [
                'name' => 'staff',
                'email' => 'staff@staff.com',
                'password' => Hash::make('password123'),
                'role' => 5
            ],
            [
                'name' => 'test',
                'email' => 'test@test.com',
                'password' => Hash::make('password123'),
                'role' => 9
            ]
        ]);
    }
}
