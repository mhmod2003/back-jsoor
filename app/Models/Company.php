<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;


class Company extends Model
{
    use HasFactory;

   /* protected $fillable = [
        'name',
        'email',
        'password',
        'description',
        'social_link',
    ];*/
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function services()
    {
        return $this->hasMany(Service::class);
    }
    
    public function ads()
    {
        return $this->hasMany(Ad::class);
    }
    
    public function requests()
    {
        return $this->hasMany(Request::class);
    }
    
    }
    
   

