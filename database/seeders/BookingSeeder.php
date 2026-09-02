<?php
namespace Database\Seeders;

use App\Models\Booking;
use App\Models\Court;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class BookingSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::first();
        $court = Court::first();

        if (!$user || !$court) {
            return;
        }

        $booking = Booking::create([
            'users_id'     => $user->id,    // FIX: users_id
            'courts_id'    => $court->id,   // FIX: courts_id
            'booking_code' => 'BK-' . strtoupper(Str::random(8)),
            'booking_date' => '2026-08-25',
            'start_time'   => '14:00:00',
            'end_time'     => '16:00:00',
            'total_price'  => 150000,
            'status'       => 'success',
        ]);

        // 2. Create Payment Linked to Booking
        Payment::create([
            'bookingsid'     => $booking->id,
            'payment_method' => 'qris',
            'amount'         => '150000',
            'paid_at'        => now(),
            'payment_status' => 'success',
        ]);
    }
}