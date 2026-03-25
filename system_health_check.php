<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Candidate;
use App\Models\User;

echo "\n============================================\n";
echo "    ELECTION SYSTEM HEALTH CHECK REPORT    \n";
echo "============================================\n";

$errors = 0;
$warnings = 0;

// 1. Check Candidate Data Integrity
echo "\n[CHECK 1] Candidate Data Integrity:\n";
$candidates = Candidate::with('user')->get();
foreach ($candidates as $c) {
    $issues = [];
    if (! $c->user) {
        $issues[] = "Orphaned Candidate Record (User ID {$c->user_id} missing)";
    } else {
        // Check Email Consistency
        $userEmail = trim(strtolower($c->user->email));
        $candEmail = trim(strtolower($c->email));

        if (empty($candEmail)) {
            $issues[] = "Missing 'email' in candidates table.";
        } elseif ($userEmail !== $candEmail) {
            $issues[] = "Email Mismatch: User[{$userEmail}] vs Candidate[{$candEmail}]";
        }

        // Check ID
        if (empty($c->candidate_id)) {
            $issues[] = "Missing 'candidate_id'.";
        }
    }

    if (! empty($issues)) {
        echo " ❌ Candidate ID {$c->id}: ".implode(', ', $issues)."\n";
        $errors++;
    } else {
        echo " ✅ Candidate {$c->id} ({$c->candidate_id}) is OK.\n";
    }
}

// 2. Check User Role Consistency
echo "\n[CHECK 2] User Role Consistency:\n";
$candidateUsers = User::where('role', 'candidate')->get();
foreach ($candidateUsers as $u) {
    if (! $u->candidate) {
        echo " ⚠️  User {$u->id} ({$u->name}) has role 'candidate' but NO candidate record.\n";
        $warnings++;
    }
}

// 3. Check for Duplicate Candidate IDs
echo "\n[CHECK 3] Uniqueness Check:\n";
$duplicates = Candidate::select('candidate_id')->groupBy('candidate_id')->havingRaw('COUNT(*) > 1')->get();
if ($duplicates->count() > 0) {
    foreach ($duplicates as $d) {
        echo " ❌ Duplicate Candidate ID found: {$d->candidate_id}\n";
        $errors++;
    }
} else {
    echo " ✅ All Candidate IDs are unique.\n";
}

// 4. Configuration Check
echo "\n[CHECK 4] Critical Configurations:\n";
// Check if routes are cached (can cause issues during dev)
if (file_exists($app->getCachedRoutesPath())) {
    echo " ⚠️  Routes are CACHED. (Run 'php artisan route:clear' if you made changes)\n";
    $warnings++;
} else {
    echo " ✅ Routes are NOT cached (Good for Dev).\n";
}

echo "\n============================================\n";
echo "SUMMARY: {$errors} Errors, {$warnings} Warnings found.\n";
echo "============================================\n";
