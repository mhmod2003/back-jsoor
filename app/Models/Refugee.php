<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Refugee extends Model
{
    protected $fillable = [
        'name',
        'phone',
        'number_of_family_member',
        'need',
        'date_of_birth',
        'status',
        'user_id',
    ];
    use HasFactory;
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

