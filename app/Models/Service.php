<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
class Service extends Model
{
    use HasFactory;
    protected $fillable = [
        'service_type',
    ];
    public function ads()
{
    return $this->hasMany(Ad::class);
}

}
