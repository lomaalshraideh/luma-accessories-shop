<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create one fixed admin
        Admin::create([
            'name' => 'Luma Admin',
            'email' => 'luma@example1.com',
            'password' => Hash::make('password'), // Use bcrypt or Hash
        ]);

        // Create 5 random admins
        Admin::factory()->count(5)->create();
    }
}


