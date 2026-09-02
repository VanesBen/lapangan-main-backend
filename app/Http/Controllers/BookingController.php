<?php
namespace App\Http\Controllers;

use App\Http\Traits\ApiResponse;
use App\Models\Booking;
use App\Http\Resources\BookingResource;
use App\Models\Payment;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class BookingController extends Controller
{
    use ApiResponse;

    /**
     * Display a listing of bookings (Admin / Customer).
     */
   public function index(Request $request): JsonResponse
    {
        $perPage = $request->query('per_page', 10);
        $user = $request->user();

        // Query booking dengan eager loading lengkap
        $query = Booking::with(['court', 'user', 'payment']);
        
        // Jika customer biasa, hanya bisa lihat booking miliknya sendiri
        if ($user && $user->role !== 'admin') {
            $query->where('users_id', $user->id); // FIX: Pakai users_id
        }

        $bookings = $query->orderBy('created_at', 'desc')->paginate($perPage);

        return $this->successResponse([
            'items' => BookingResource::collection($bookings),
            'meta' => [
                'current_page' => $bookings->currentPage(),
                'last_page'    => $bookings->lastPage(),
                'per_page'     => $bookings->perPage(),
                'total'        => $bookings->total(),
            ]
        ], "Berhasil mengambil data booking");
    }

    /**
     * Store a newly created booking in storage.
     */
    public function store(Request $request): JsonResponse
    {
        // 1. Validasi Input Request dari Frontend
        $validated = $request->validate([
            'users_id'       => 'required|exists:users,id',
            'courts_id'      => 'required|exists:courts,id',
            'booking_date'   => 'required|date|date_format:Y-m-d',
            'start_time'     => 'required|date_format:H:i:s',
            'end_time'       => 'required|date_format:H:i:s|after:start_time',
            'total_price'    => 'required|integer|min:0',
            'payment_method' => 'required|string|max:50',
        ]);

        // 2. Overlapping Check
        $isOverlap = Booking::where('courts_id', $validated['courts_id'])
            ->where('booking_date', $validated['booking_date'])
            ->where(function ($query) use ($validated) {
                $query->where('start_time', '<', $validated['end_time'])
                    ->where('end_time', '>', $validated['start_time']);
            })
            ->where('status', '!=', 'cancelled')
            ->exists();

        if ($isOverlap) {
            return $this->validationErrorResponse("Maaf, slot jam pada tanggal tersebut sudah di-booking orang lain!");
        }

        try {
            $booking = DB::transaction(function () use ($validated) {
                // A. BUAT BOOKING DULUAN (Biar dapet Booking ID)
                $newBooking = Booking::create([
                    'users_id'     => $validated['users_id'],
                    'courts_id'    => $validated['courts_id'],
                    'booking_code' => 'BK-' . strtoupper(Str::random(8)),
                    'booking_date' => $validated['booking_date'],
                    'start_time'   => $validated['start_time'],
                    'end_time'     => $validated['end_time'],
                    'total_price'  => $validated['total_price'],
                    'status'       => 'success',
                ]);

                Payment::create([
                    'bookings_id'    => $newBooking->id, // FIX: Pakai bookings_id
                    'payment_method' => $validated['payment_method'],
                    'amount'         => (string) $validated['total_price'],
                    'paid_at'        => now(),
                    'payment_status' => 'success',
                ]);

                return $newBooking;
            });

            return $this->createdResponse(
                new BookingResource($booking->load(['court', 'user', 'payment'])),
                "Booking dan pembayaran berhasil dibuat"
            );
        } catch (\Exception $e) {
            return $this->errorResponse("Gagal memproses transaksi: " . $e->getMessage());
        }
    }
    /**
     * Display the specified booking.
     */
    public function show(string $id): JsonResponse
    {
        // Load relasi court, user, DAN payment
        $booking = Booking::with(['court', 'user', 'payment'])->find($id);

        if (!$booking) {
            return $this->notFoundResponse("Data booking tidak ditemukan");
        }

        return $this->successResponse(
            new BookingResource($booking),
            "Detail booking berhasil ditemukan"
        );
    }

    /**
     * Update booking status (e.g. Cancel / Complete by Admin/User).
     */
    public function update(Request $request, string $id): JsonResponse
    {
        $booking = Booking::find($id);

        if (!$booking) {
            return $this->notFoundResponse("Data booking tidak ditemukan");
        }

        $validated = $request->validate([
            'status' => 'required|string|in:pending,success,cancelled',
        ]);

        $booking->update($validated);

        return $this->successResponse(
            new BookingResource($booking),
            "Status booking berhasil diperbarui"
        );
    }

    /**
     * Cancel / Delete Booking.
     */
    public function destroy(string $id): JsonResponse
    {
        $booking = Booking::find($id);

        if (!$booking) {
            return $this->notFoundResponse("Data booking tidak ditemukan");
        }

        $booking->delete();

        return $this->successResponse(null, "Booking berhasil dihapus");
    }
}