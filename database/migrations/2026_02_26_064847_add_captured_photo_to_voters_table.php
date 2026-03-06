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
            $table->string('captured_photo')->nullable()->after('has_voted');
        });
    }

    public function down(): void
    {
        Schema::table('voters', function (Blueprint $table) {
            $table->dropColumn('captured_photo');
        });
    }
};
