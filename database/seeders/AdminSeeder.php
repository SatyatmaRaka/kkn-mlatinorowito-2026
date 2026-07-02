<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@kkn.com'],
            [
                'name' => 'Admin KKN Mlatinorowito',
                'email' => 'admin@kkn.com',
                'password' => Hash::make('admin123'),
            ]
        );
    }
}
