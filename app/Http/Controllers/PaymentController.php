<?php

namespace App\Http\Controllers;

use App\Http\Resources\PaymentResource;
use App\Http\Traits\ApiResponse;
use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    use ApiResponse;
    public function index(Request $request)
    {
        $perPage = $request->query('per_page', 10);

        // Ambil data lapangan
        $courts = Payment::paginate($perPage);

        return $this->successResponse([
            'items' => PaymentResource::collection($courts),
            'meta' => [
                'current_page' => $courts->currentPage(),
                'last_page'    => $courts->lastPage(),
                'per_page'     => $courts->perPage(),
                'total'        => $courts->total(),
            ]
        ], "Berhasil mengambil data Pay");
    }
}
