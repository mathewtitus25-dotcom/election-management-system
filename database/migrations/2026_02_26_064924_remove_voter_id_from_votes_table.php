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
        Schema::table('votes', function (Blueprint $table) {
            // Drop the unique index first (SQLite requires this before dropping the column)
            if (Schema::hasColumn('votes', 'voter_id')) {
                try { $table->dropUnique(['voter_id']); } catch (\Exception $e) {}
                try { $table->dropForeign(['voter_id']); } catch (\Exception $e) {}
                $table->dropColumn('voter_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('votes', function (Blueprint $table) {
            $table->foreignId('voter_id')->nullable()->constrained('users');
            $table->unique('voter_id');
        });
    }
};
