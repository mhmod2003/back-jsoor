<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Request extends Model
{
    use HasFactory;
    public function user()
{
    return $this->belongsTo(User::class);
}

public function ad()
{
    return $this->belongsTo(Ad::class, 'ads_id');
}

public function company()
{
    return $this->belongsTo(Company::class);
}

}
