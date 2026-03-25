<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class FixPostgresSequences extends Command
{
    protected $signature = 'db:fix-sequences';

    protected $description = 'Fix PostgreSQL sequences for all tables after MySQL-to-PostgreSQL migration';

    public function handle()
    {
        if (config('database.default') !== 'pgsql') {
            $this->error('This command only works with PostgreSQL.');

            return 1;
        }

        $tables = [
            'users',
            'panchayats',
            'voters',
            'candidates',
            'blos',
            'votes',
            'election_config',
            'jobs',
            'failed_jobs',
            'cache',
            'cache_locks',
            'migrations',
        ];

        foreach ($tables as $table) {
            try {
                // Check if the table exists
                $exists = DB::select("SELECT to_regclass('public.{$table}') AS tbl");
                if (! $exists[0]->tbl) {
                    $this->warn("Table '{$table}' does not exist. Skipping.");

                    continue;
                }

                // Check if the table has an 'id' column
                $hasId = DB::select("
                    SELECT column_name FROM information_schema.columns 
                    WHERE table_schema = 'public' AND table_name = ? AND column_name = 'id'
                ", [$table]);

                if (empty($hasId)) {
                    $this->warn("Table '{$table}' has no 'id' column. Skipping.");

                    continue;
                }

                $seqName = "{$table}_id_seq";

                // Create the sequence if it doesn't exist
                DB::statement("CREATE SEQUENCE IF NOT EXISTS {$seqName}");

                // Set the default value for the id column to use the sequence
                DB::statement("ALTER TABLE {$table} ALTER COLUMN id SET DEFAULT nextval('{$seqName}')");

                // Set the sequence owner to the column
                DB::statement("ALTER SEQUENCE {$seqName} OWNED BY {$table}.id");

                // Reset the sequence value to the max existing id
                $maxId = DB::table($table)->max('id') ?? 0;
                DB::statement("SELECT setval('{$seqName}', ?, false)", [$maxId + 1]);

                $this->info("✅ Fixed sequence for '{$table}' (next ID: ".($maxId + 1).')');

            } catch (\Exception $e) {
                $this->error("❌ Failed to fix '{$table}': ".$e->getMessage());
            }
        }

        $this->newLine();
        $this->info('🎉 All sequences fixed! Your database is ready.');

        return 0;
    }
}
