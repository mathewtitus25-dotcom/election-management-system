<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('votes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('voter_id')->constrained('users'); // Linking to User ID who voted
            $table->foreignId('candidate_id')->constrained('candidates');
            $table->foreignId('panchayat_id')->constrained('panchayats');
            $table->timestamps();

            // Ensure a voter can only vote once
            $table->unique('voter_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('votes');
    }
};
