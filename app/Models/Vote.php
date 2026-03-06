<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vote extends Model
{
    use HasFactory;

    protected $fillable = [
        'candidate_id',
        'panchayat_id',
    ];

    public function candidate()
    {
        return $this->belongsTo(Candidate::class);
    }

    public function panchayat()
    {
        return $this->belongsTo(Panchayat::class);
    }
}
