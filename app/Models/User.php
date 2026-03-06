<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'panchayat_id',
        'otp',
        'otp_expires_at',
        'is_verified',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'otp', // Hide OTP from arrays
    ];

    protected $casts = [
        'password' => 'hashed',
        'otp_expires_at' => 'datetime',
        'is_verified' => 'boolean',
    ];

    public function panchayat()
    {
        return $this->belongsTo(Panchayat::class);
    }

    public function voter()
    {
        return $this->hasOne(Voter::class);
    }

    public function blo()
    {
        return $this->hasOne(BLO::class);
    }

    public function candidate()
    {
        return $this->hasOne(Candidate::class);
    }
    
    // Helper to check role
    public function hasRole($role)
    {
        return $this->role === $role;
    }
}
