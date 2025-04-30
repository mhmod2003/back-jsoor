<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'password',
        'description',
        'social_link',
    ];
    public function ads()
{
    return $this->hasMany(Ad::class);
}

public function services()
{
    return $this->hasMany(Service::class);
}

public function meets()
{
    return $this->hasMany(Meet::class);
}

public function maps()
{
    return $this->hasMany(Map::class);
}

public function requests()
{
    return $this->hasMany(Request::class);
}

}
