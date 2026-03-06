<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Candidate extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'email',
        'candidate_id',
        'manifesto',
        'status',
        'votes_count',
        'dob',
        'gender',
        'mobile',
        'voter_id',
        'aadhaar',
        'address',
        'qualification',
        'photo',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
