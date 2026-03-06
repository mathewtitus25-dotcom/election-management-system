<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Voter extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'voter_id_number',
        'aadhaar_number',
        'mobile',
        'dob',
        'status',
        'has_voted',
        'captured_photo',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    // Helper to access Panchayat via User
    public function getPanchayatAttribute()
    {
        return $this->user->panchayat;
    }
}
