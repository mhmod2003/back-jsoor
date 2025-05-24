<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Refugee extends Model
{
   /* protected $fillable = [
        'name',
        'number_of_family_members',
        'need',
        'date_of_birth',
        'user_id',
    ];*/
    use HasFactory;
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

