<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create a role (optional if role are needed)
        $adminRole = Role::where('name', 'admin')->first();

        // If the role doesn't exist, create it
        if (! $adminRole) {
            $adminRole = Role::create(['name' => 'admin']);
        }

        // Create another user
        User::create([
            'name' => 'Mikeu',
            'email' => 'mikeumikeudeh@gmail.com',
            'password' => Hash::make('Mikeu28*'),
            'role_id' => $adminRole->id,
            'email_verified_at' => Carbon::now(), // Menambahkan email_verified_at
        ]);

        User::create([
            'name' => 'Admin',
            'email' => 'adminspp@gmail.com',
            'password' => Hash::make('Minimal8@'),
            'role_id' => $adminRole->id,
            'email_verified_at' => Carbon::now(), // Menambahkan email_verified_at
        ]);
    }
}