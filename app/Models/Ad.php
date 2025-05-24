<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Ad extends Model
{
    use HasFactory;
    public function company()
{
    return $this->belongsTo(Company::class);
}

public function requests()
{
    return $this->hasMany(Request::class, 'ads_id');
}

}