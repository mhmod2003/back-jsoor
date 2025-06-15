<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;


class Company extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'description',
        'social_link',
        'map_location',
        'user_id',
        'status'
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function ads()
    {
        return $this->hasMany(Ad::class);
    }
    
     }
    
    
    
   

