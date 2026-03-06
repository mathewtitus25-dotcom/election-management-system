<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('candidates', function (Blueprint $table) {
            $table->date('dob')->nullable();
            $table->string('gender')->nullable();
            $table->string('mobile')->nullable();
            $table->string('voter_id')->nullable();
            $table->string('aadhaar')->nullable();
            $table->text('address')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('candidates', function (Blueprint $table) {
            $table->dropColumn(['dob', 'gender', 'mobile', 'voter_id', 'aadhaar', 'address']);
        });
    }
};
