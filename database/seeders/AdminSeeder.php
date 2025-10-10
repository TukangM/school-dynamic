<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('users')->insert([
            'name' => 'Admin Penjelajah',
            'email' => 'admin@admin.com',
            'password' => Hash::make('Admin'),
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }
}