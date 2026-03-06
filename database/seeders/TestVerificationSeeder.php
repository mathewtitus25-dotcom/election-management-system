<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Panchayat;
use App\Models\BLO;
use App\Models\Voter;
use App\Models\Candidate;
use App\Models\ElectionConfig;
use App\Models\Vote;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class TestVerificationSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Ensure Panchayats exist
        $p1 = Panchayat::firstOrCreate(['id' => 1], ['name' => 'Panchayat 1', 'description' => 'Test Panchayat']);

        // 2. Admin
        User::updateOrCreate(
            ['email' => 'admin@example.com'],
            ['name' => 'System Admin', 'password' => Hash::make('password'), 'role' => 'admin']
        );

        // 3. BLO
        $bloUser = User::updateOrCreate(
            ['email' => 'test-blo@example.com'],
            ['name' => 'Test BLO', 'password' => Hash::make('password'), 'role' => 'blo', 'panchayat_id' => 1]
        );
        BLO::updateOrCreate(['user_id' => $bloUser->id], ['is_active' => true]);

        // 4. Candidate
        $candidateUser = User::updateOrCreate(
            ['email' => 'test-candidate@example.com'],
            ['name' => 'Test Candidate', 'password' => Hash::make('password'), 'role' => 'candidate', 'panchayat_id' => 1]
        );
        
        Candidate::updateOrCreate(['user_id' => $candidateUser->id], [
            'candidate_id' => 'CAN-001',
            'dob' => '1990-01-01',
            'gender' => 'Male',
            'voter_id' => 'VOTER-CAN-001',
            'aadhaar' => '123456789012',
            'mobile' => '9876543210',
            'qualification' => 'Graduate',
            'manifesto' => 'Working for the people.',
            'status' => 'approved',
            'votes_count' => 0
        ]);

        // 5. Voter
        $voterUser = User::updateOrCreate(
            ['email' => 'test-voter@example.com'],
            ['name' => 'Test Voter', 'password' => Hash::make('password'), 'role' => 'voter', 'panchayat_id' => 1]
        );
        
        Voter::updateOrCreate(['user_id' => $voterUser->id], [
            'voter_id_number' => 'VOTER-123',
            'dob' => '1995-01-01',
            'status' => 'approved',
            'has_voted' => false
        ]);

        // 6. Reset Election Config
        ElectionConfig::updateOrCreate(['id' => 1], ['is_active' => true]);
        
        // Clear previous votes
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Vote::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        
        // Reset Panchayat publish status
        $p1->update(['is_result_published' => false]);
    }
}
