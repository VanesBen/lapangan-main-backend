<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PricingRule extends Model
{

    protected $fillable = [
        'courts_id',
        'day_type',
        'start_hour',
        'end_hour',
        'price_per_hour',
    ];

    public function court(): BelongsTo
    {
        return $this->belongsTo(Court::class, 'courts_id', 'id');
    }
}
