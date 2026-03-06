<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Panchayat extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description', 'is_result_published', 'was_published'];

    protected $casts = [
        'is_result_published' => 'boolean',
        'was_published' => 'boolean',
    ];

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function voters()
    {
        return $this->hasManyThrough(Voter::class, User::class);
    }

    public function candidates()
    {
        return $this->hasManyThrough(Candidate::class, User::class);
    }
    
    // Helper to get candidates for this panchayat directly from candidates table if needed,
    // but candidates are linked to users which are linked to panchayats. 
    // However, Candidate model stores the manifesto, etc.
}
