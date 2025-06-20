<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Notification extends Model
{
    use HasFactory;
    protected $fillable = [
        'not_id',
        'user_id',
        'title',
        'description',
    ];


public function requests()
{
    return $this->belongsTo(RequestT::class, 'not_id');
}

}
