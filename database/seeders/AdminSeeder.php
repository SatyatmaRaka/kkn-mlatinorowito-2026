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
            ['username' => 'kkn_mlati26'],
            [
                'name' => 'Admin KKN Mlatinorowito',
                'password' => Hash::make(env('ADMIN_DEFAULT_PASSWORD', 'MlatinorowitoHebat26!')),
            ]
        );
    }
}
