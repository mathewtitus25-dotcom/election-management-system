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
        if (!Schema::hasColumn('panchayats', 'is_result_published')) {
            Schema::table('panchayats', function (Blueprint $table) {
                $table->boolean('is_result_published')->default(false)->after('name');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('panchayats', 'is_result_published')) {
            Schema::table('panchayats', function (Blueprint $table) {
                $table->dropColumn('is_result_published');
            });
        }
    }
};
