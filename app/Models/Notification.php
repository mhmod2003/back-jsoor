<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Notification extends Model
{
    use HasFactory;
    public function user()
{
    return $this->belongsTo(User::class);
}

public function requests()
{
    return $this->hasMany(Request::class, 'not_id');
}

}
