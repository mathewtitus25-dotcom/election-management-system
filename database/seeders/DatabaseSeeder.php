<?php

namespace Database\Seeders;

use App\Models\ElectionConfig;
use App\Models\Panchayat;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Create Default Panchayats (Matches Motoko `defaultPanchayats`)
        $panchayats = [
            ['id' => 1, 'name' => 'Panchayat 1', 'description' => 'Description 1'],
            ['id' => 2, 'name' => 'Panchayat 2', 'description' => 'Description 2'],
            ['id' => 3, 'name' => 'Panchayat 3', 'description' => 'Description 3'],
        ];

        foreach ($panchayats as $p) {
            Panchayat::firstOrCreate(['id' => $p['id']], $p);
        }

        // 2. Create an Admin User (To bootstrap the system)
        if (! User::where('email', 'admin@example.com')->exists()) {
            User::create([
                'name' => 'System Admin',
                'email' => 'admin@example.com',
                'password' => 'password', // Change validation in production
                'role' => 'admin',
            ]);
        }

        // 3. Initialize Election Config (Matches Motoko init)
        if (! ElectionConfig::exists()) {
            ElectionConfig::create([
                'is_active' => false,
            ]);
        }
    }
}
