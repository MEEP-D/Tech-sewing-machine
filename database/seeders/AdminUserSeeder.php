<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'admin@techsewing.vn'],
            [
                'name'     => 'Tech Sewing Admin',
                'password' => Hash::make('Admin@2026!'),
                'is_admin' => true,
            ]
        );
    }
}
