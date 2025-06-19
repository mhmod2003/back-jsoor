<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RequestT extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'ads_id',
        'not_id',
        'status',
    ];
    public function user()
{
    return $this->belongsTo(User::class);
}


public function ad()
{
    return $this->hasMany(Ad::class, 'ads_id');
}

public function notification()
{
    return $this->belongsTo(Notification::class, 'not_id');
}

}
