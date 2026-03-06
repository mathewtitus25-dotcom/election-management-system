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
        Schema::table('panchayats', function (Blueprint $table) {
            $table->boolean('was_published')->default(false)->after('is_result_published');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('panchayats', function (Blueprint $table) {
            $table->dropColumn('was_published');
        });
    }
};
