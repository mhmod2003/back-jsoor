<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Request extends Model
{
    use HasFactory;
    public function user()
{
    return $this->belongsTo(User::class);
}

public function company()
{
    return $this->belongsTo(Company::class);
}

public function ad()
{
    return $this->belongsTo(Ad::class, 'ads_id');
}

public function notification()
{
    return $this->belongsTo(Notification::class, 'not_id');
}

}
