<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BLOController;
use App\Http\Controllers\CandidateController;
use App\Http\Controllers\ElectionController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\RegistrationController;
use App\Http\Controllers\VoterController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('welcome');

// Authentication Routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Forgot Password Routes
Route::get('/forgot-password', [ForgotPasswordController::class, 'showForm'])->name('password.request');
Route::post('/forgot-password', [ForgotPasswordController::class, 'sendOtp'])->name('password.send');
Route::get('/reset-password', [ForgotPasswordController::class, 'showReset'])->name('password.reset.form');
Route::post('/reset-password', [ForgotPasswordController::class, 'reset'])->name('password.reset');

// Registration Routes
Route::get('/register', [RegistrationController::class, 'showRegister'])->name('register');
Route::post('/register', [RegistrationController::class, 'register'])->name('register.post');
Route::get('/otp-verify', [RegistrationController::class, 'showOtp'])->name('otp.verify');
Route::post('/otp-verify', [RegistrationController::class, 'verifyOtp'])->name('otp.post');

// Candidate Application (Public)
Route::get('/candidate/apply', [CandidateController::class, 'showRegistrationForm'])->name('candidate.register');
Route::post('/candidate/apply', [CandidateController::class, 'register'])->name('candidate.register.post');

// Protected Routes
Route::middleware(['auth'])->group(function () {

    // Candidate Area (Approved Candidates only)
    Route::middleware(['role:candidate'])->group(function () {
        Route::get('/candidate/dashboard', [CandidateController::class, 'dashboard'])->name('candidate.dashboard');
    });

    // Voter Dashboard — strictly voters only
    Route::middleware(['role:voter'])->group(function () {
        Route::get('/voter/dashboard', [VoterController::class, 'dashboard'])->name('voter.dashboard');
        Route::post('/vote', [VoterController::class, 'vote'])->name('vote.post');
    });

    // BLO Dashboard
    Route::middleware(['role:blo'])->group(function () {
        Route::get('/blo/dashboard', [BLOController::class, 'dashboard'])->name('blo.dashboard');
        Route::post('/blo/approve/{id}', [BLOController::class, 'approveVoter'])->name('blo.approve');
        Route::post('/blo/reject/{id}', [BLOController::class, 'rejectVoter'])->name('blo.reject');
        Route::post('/blo/publish', [BLOController::class, 'publishResult'])->name('blo.publish');
        Route::post('/blo/unpublish', [BLOController::class, 'unpublishResult'])->name('blo.unpublish');
    });

    // Admin Dashboard
    Route::middleware(['role:admin'])->group(function () {
        Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
        Route::post('/admin/blo', [AdminController::class, 'createBLO'])->name('admin.blo.create');
        Route::post('/admin/blo/{id}/toggle', [AdminController::class, 'toggleBLO'])->name('admin.blo.toggle'); // Added Route
        Route::post('/admin/election', [AdminController::class, 'updateElection'])->name('admin.election.update');

        // Candidate Management
        Route::post('/admin/candidate/approve/{id}', [AdminController::class, 'approveCandidate'])->name('admin.candidate.approve');
        Route::post('/admin/candidate/reject/{id}', [AdminController::class, 'rejectCandidate'])->name('admin.candidate.reject');
        Route::post('/admin/candidate/remove/{id}', [AdminController::class, 'removeCandidate'])->name('admin.candidate.remove');

        // Voter Management & Verification
        Route::get('/admin/voters', [AdminController::class, 'votersList'])->name('admin.voters.index');
        Route::delete('/admin/panchayats/{id}/voters', [AdminController::class, 'deleteAllVoters'])->name('admin.voters.deleteAll');
    });

    // Public Results (Authenticated for now)
    Route::get('/results', [ElectionController::class, 'index'])->name('results');
});
