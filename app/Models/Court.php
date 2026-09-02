<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Court extends Model
{
    protected $fillable = [
        'name',
        'description',
        'is_active',
        'facilities',
        'rules',
        'location',
        'photo'
    ];

    public function pricingRules(): HasMany
    {
        return $this->hasMany(PricingRule::class, 'courts_id', 'id');
    }
    

}
