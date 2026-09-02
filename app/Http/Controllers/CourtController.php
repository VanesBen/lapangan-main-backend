<?php

namespace App\Http\Controllers;


use App\Http\Resources\CourtResource;
use App\Http\Traits\ApiResponse;
use App\Models\Court;
use Illuminate\Http\Request;

class CourtController extends Controller
{
    use ApiResponse;
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $perPage = $request->query('per_page', 10);

        // Ambil data lapangan
        $courts = Court::paginate($perPage);

        return $this->successResponse([
            'items' => CourtResource::collection($courts),
            'meta' => [
                'current_page' => $courts->currentPage(),
                'last_page'    => $courts->lastPage(),
                'per_page'     => $courts->perPage(),
                'total'        => $courts->total(),
            ]
        ], "Berhasil mengambil data lapangan");
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'required|string',
            'is_active'   => 'required|string|max:10', 
            'facilities'  => 'required|string|max:500',
            'rules'       => 'required|string|max:500'
        ]);

        $court = Court::create($validated);

        return $this->createdResponse(new CourtResource($court), "Lapangan berhasil ditambahkan");
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
            'name'        => 'sometimes|required|string|max:255',
            'description' => 'sometimes|required|string',
            'is_active'   => 'sometimes|required|string|max:10',
            'facilities'  => 'sometimes|required|string|max:500',
            'rules'       => 'sometimes|required|string|max:500'
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
}
