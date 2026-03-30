<?php

namespace Database\Seeders;

use App\Models\ElectionConfig;
use App\Models\Panchayat;
use App\Models\User;
use App\Models\BLO;
use App\Models\Voter;
use App\Models\Candidate;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

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

        // 2. Create an Admin User
        if (! User::where('email', 'admin@example.com')->exists()) {
            User::create([
                'name' => 'System Admin',
                'email' => 'admin@example.com',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'is_verified' => true,
            ]);
        }

        // 3. Create a BLO (Booth Level Officer) User
        if (! User::where('email', 'blo@example.com')->exists()) {
            $bloUser = User::create([
                'name' => 'Sample BLO',
                'email' => 'blo@example.com',
                'password' => Hash::make('password'),
                'role' => 'blo',
                'panchayat_id' => 1,
                'is_verified' => true,
            ]);
            BLO::create([
                'user_id' => $bloUser->id,
                'is_active' => true,
            ]);
        }

        // 4. Create a Voter User
        if (! User::where('email', 'voter@example.com')->exists()) {
            $voterUser = User::create([
                'name' => 'Sample Voter',
                'email' => 'voter@example.com',
                'password' => Hash::make('password'),
                'role' => 'voter',
                'panchayat_id' => 1,
                'is_verified' => true,
            ]);
            Voter::create([
                'user_id' => $voterUser->id,
                'voter_id_number' => 'VOTER12345',
                'aadhaar_number' => '123456789012',
                'mobile' => '9876543210',
                'dob' => Carbon::now()->subYears(25)->format('Y-m-d'),
                'status' => 'approved',
                'has_voted' => false,
            ]);
        }

        // 5. Create a Candidate User
        if (! User::where('email', 'candidate@example.com')->exists()) {
            $candidateUser = User::create([
                'name' => 'Sample Candidate',
                'email' => 'candidate@example.com',
                'password' => Hash::make('password'),
                'role' => 'candidate',
                'panchayat_id' => 1,
                'is_verified' => true,
            ]);
            Candidate::create([
                'user_id' => $candidateUser->id,
                'email' => clone $candidateUser->email,
                'candidate_id' => 'CAND1234',
                'manifesto' => 'My vision is transparency and growth.',
                'status' => 'approved',
                'votes_count' => 0,
                'dob' => Carbon::now()->subYears(35)->format('Y-m-d'),
                'gender' => 'Male',
                'mobile' => '9876543211',
                'voter_id' => 'VOTER99999',
                'aadhaar' => '987654321098',
                'address' => 'Sample Address, Panchayat 1',
            ]);
        }

        // 6. Initialize Election Config
        if (! ElectionConfig::exists()) {
            ElectionConfig::create([
                'is_active' => false,
            ]);
        }
    }
}
