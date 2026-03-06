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
        Schema::table('voters', function (Blueprint $table) {
            $table->string('aadhaar_number', 16)->nullable()->after('voter_id_number');
            $table->string('mobile', 10)->nullable()->after('aadhaar_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('voters', function (Blueprint $table) {
            $table->dropColumn(['aadhaar_number', 'mobile']);
        });
    }
};
