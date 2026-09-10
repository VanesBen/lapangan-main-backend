<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCourtRequest;
use App\Http\Resources\CourtResource;
use App\Http\Traits\ApiResponse;
use App\Models\Booking;
use App\Models\Court;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CourtController extends Controller
{
    use ApiResponse;
    /**
     * Display a listing of the resource.
     */
    public function publicIndex(Request $request)
    {
        $perPage = $request->query('per_page', 12);

        // Publik hanya boleh lihat lapangan yang aktif
        $query = Court::where('is_active', true)
            ->with('pricingRules');

        // Terapkan filter & sort yang sama persis
        $courts = $this->applyFiltersAndSorting($query, $request)
            ->paginate($perPage);

        return $this->successResponse([
            'items' => CourtResource::collection($courts),
            'meta'  => [
                'current_page' => $courts->currentPage(),
                'last_page'    => $courts->lastPage(),
                'per_page'     => $courts->perPage(),
                'total'        => $courts->total(),
            ]
        ], "Berhasil mengambil katalog lapangan");
    }

    
    public function index(Request $request)
    {
        $perPage = $request->query('per_page', 10);

        // Kunci ownership admin
        $query = Court::where('user_id', $request->user()->id)
            ->with('pricingRules');

        // Terapkan filter & sort
        $courts = $this->applyFiltersAndSorting($query, $request)
            ->paginate($perPage);

        return $this->successResponse([
            'items' => CourtResource::collection($courts),
            'meta'  => [
                'current_page' => $courts->currentPage(),
                'last_page'    => $courts->lastPage(),
                'per_page'     => $courts->perPage(),
                'total'        => $courts->total(),
            ]
        ], "Berhasil mengambil data lapangan saya");
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCourtRequest $request)
    {
        $validated = $request->validated();

        return DB::transaction(function () use ($validated, $request) {
            $court = Court::create([
                'user_id'     => $request->user()->id,
                'name'        => $validated['name'],
                'category'    => $validated['category'],
                'location'    => $validated['location'],
                'photo'       => $validated['photo'],
                'facilities'  => $validated['facilities'] ?? null,
                'rules'       => $validated['rules'] ?? null,
                'description' => $validated['description'] ?? null,
                'is_active'   => filter_var($request->is_active ?? true, FILTER_VALIDATE_BOOLEAN),
        ]);

        foreach ($validated['pricing_rules'] as $rule) {
            $court->pricingRules()->create([
                'day_type'       => strtolower($rule['day_type']),
                'start_hour'     => $rule['start_hour'],
                'end_hour'       => $rule['end_hour'],
                'price_per_hour' => $rule['price_per_hour'],
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Lapangan beserta aturan tarif berhasil dibuat.',
            'data'    => $court->load('pricingRules'),
        ], 201);
    });
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $court = Court::with('pricingRules')->find($id);

        if (!$court) {
            return $this->notFoundResponse("Lapangan tidak ditemukan");
        }

        return $this->successResponse(new CourtResource($court), "Lapangan berhasil ditemukan");
    }


    public function checkAvailability(Request $request, string $id)
    {
        $court = Court::find($id);
        if (!$court) {
            return $this->notFoundResponse("Lapangan tidak ditemukan");
        }

        $date = $request->query('date', now()->format('Y-m-d'));

        $bookedHours = Booking::where('courts_id', $id)
            ->where('booking_date', $date)
            ->where('status', '!=', 'cancelled')
            ->get(['start_time', 'end_time'])
            ->map(function ($booking) {
                $start = (int) substr($booking->start_time, 0, 2);
                $end = (int) substr($booking->end_time, 0, 2);
                return range($start, $end - 1);
            })
            ->flatten()
            ->unique()
            ->values();

        return $this->successResponse([
            'court_id'     => (int) $id,
            'date'         => $date,
            'booked_hours' => $bookedHours,
        ], "Data ketersediaan slot berhasil diambil");
    }
    

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $court = Court::find($id);

        if (!$court) {
            return $this->notFoundResponse("Lapangan tidak ditemukan");
        }

        $validated = $request->validate([
            'name'          => 'sometimes|required|string|max:255',
            'description'   => 'sometimes|required|string',
            'is_active'     => 'sometimes|required|boolean|max:10',
            'facilities'    => 'sometimes|required|string|max:500',
            'location'      => 'sometimes|required|string|max:100',
            'rules'         => 'sometimes|required|string|max:500',
            'photo'         => 'sometimes|nullable|string|max:3000000',
            'category'      => 'sometimes|required|string|max:100|in:Basket,Futsal / Sepakbola,Badminton,Padel,Tenis'
        ]);

        $court->update($validated);

        return $this->successResponse(new CourtResource($court), "Data lapangan berhasil di-update");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $court = Court::find($id);

        if (!$court) {
            return $this->notFoundResponse("Lapangan tidak ditemukan");
        }

        $court->delete();

        return $this->successResponse(null, "Lapangan berhasil dihapus");
    }

    private function applyFiltersAndSorting($query, Request $request)
    {
        return $query

            ->when($request->filled('search'), function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->query('search') . '%');
            })

            ->when($request->filled('category'), function ($q) use ($request) {
                $q->where('category', $request->query('category'));
            })

            // 4. Sorting Dinamis (?sort_by=name&sort_direction=asc)
            ->when($request->filled('sort_by'), function ($q) use ($request) {
                $allowedColumns = ['name', 'category', 'created_at'];
                $sortBy = in_array($request->query('sort_by'), $allowedColumns) 
                    ? $request->query('sort_by') 
                    : 'created_at';

                $direction = strtolower($request->query('sort_direction')) === 'asc' ? 'asc' : 'desc';
                $q->orderBy($sortBy, $direction);
            }, function ($q) {
                // Default sorting kalau tidak ada parameter sort di URL
                $q->latest();
            });
    }
}
