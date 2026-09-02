<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    protected $fillable = [
        'bookings_id',
        'payment_method',
        'amount',
        'paid_at',
        'payment_status',
    ];

    // Relasi kebalikan ke Booking
    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class, 'bookings_id', 'id');
    }
}
