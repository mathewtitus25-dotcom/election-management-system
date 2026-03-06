<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BLO extends Model
{
    use HasFactory;

    protected $table = 'blos'; // Custom table name if needed, but plural is blos

    protected $fillable = [
        'user_id',
        'is_active',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
