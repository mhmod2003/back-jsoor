<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Ad extends Model
{
    use HasFactory; 
    protected $fillable = [
        'company_id',
        'ads_id',
        'service_id',
        'title',
        'meet_link',
        'description',
        'location',
        'start_date',
        'end_date',
    ];
    public function company()
{
    return $this->belongsTo(Company::class);
}

public function requests()
{
    return $this->belongsTo(RequestT::class, 'ads_id');
}

public function services()
{
    return $this->belongsTo(Service::class, 'service_id');
}

}