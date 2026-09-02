<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Booking extends Model
{
    protected $fillable = [
        'users_id',
        'courts_id',
        'booking_code',
        'booking_date',
        'start_time',
        'end_time',
        'total_price',
        'status',
    ];

    // Relasi ke User
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'users_id', 'id');
    }

    // Relasi ke Court
    public function court(): BelongsTo
    {
        return $this->belongsTo(Court::class, 'courts_id', 'id');
    }

    // Relasi 1 Booking punya 1 Payment
    public function payment(): HasOne
    {
        return $this->hasOne(Payment::class, 'bookings_id', 'id');
    }
}
