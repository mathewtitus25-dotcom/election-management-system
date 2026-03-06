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
            $table->string('email')->after('user_id')->nullable();
        });

        // Populate existing candidates
        $candidates = DB::table('candidates')->get();
        foreach ($candidates as $c) {
            $user = DB::table('users')->where('id', $c->user_id)->first();
            if ($user) {
                DB::table('candidates')->where('id', $c->id)->update(['email' => $user->email]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('candidates', function (Blueprint $table) {
            $table->dropColumn('email');
        });
    }
};
