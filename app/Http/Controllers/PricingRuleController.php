<?php

namespace App\Http\Controllers;

use App\Http\Traits\ApiResponse;
use App\Models\PricingRule;
use App\Http\Resources\PricingRuleResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PricingRuleController extends Controller
{
    use ApiResponse;

    public function index(Request $request): JsonResponse
    {
        $perPage = $request->query('per_page', 10);
        $rules = PricingRule::with('court')->paginate($perPage);

        return $this->successResponse([
            'items' => PricingRuleResource::collection($rules),
            'meta' => [
                'current_page' => $rules->currentPage(),
                'last_page'    => $rules->lastPage(),
                'per_page'     => $rules->perPage(),
                'total'        => $rules->total(),
            ]
        ], "Berhasil mengambil data aturan harga");
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'courts_id'      => 'required|exists:courts,id',
            'day_type'       => 'required|string|max:50',
            'start_hour'     => 'required|integer|min:0|max:23',
            'end_hour'       => 'required|integer|min:1|max:24',
            'price_per_hour' => 'required|integer|min:0',
        ]);

        $rule = PricingRule::create($validated);

        return $this->createdResponse(new PricingRuleResource($rule), "Aturan harga berhasil ditambahkan");
    }

    public function show(string $id): JsonResponse
    {
        $rule = PricingRule::with('court')->find($id);

        if (!$rule) {
            return $this->notFoundResponse("Aturan harga tidak ditemukan");
        }

        return $this->successResponse(new PricingRuleResource($rule), "Aturan harga berhasil ditemukan");
    }

    public function update(Request $request, string $id): JsonResponse
    {
        $rule = PricingRule::find($id);

        if (!$rule) {
            return $this->notFoundResponse("Aturan harga tidak ditemukan");
        }

        $validated = $request->validate([
            'courts_id'      => 'sometimes|required|exists:courts,id',
            'day_type'       => 'sometimes|required|string|max:50',
            'start_hour'     => 'sometimes|required|integer|min:0|max:23',
            'end_hour'       => 'sometimes|required|integer|min:1|max:24',
            'price_per_hour' => 'sometimes|required|integer|min:0',
        ]);

        $rule->update($validated);

        return $this->successResponse(new PricingRuleResource($rule), "Aturan harga berhasil di-update");
    }

    public function destroy(string $id): JsonResponse
    {
        $rule = PricingRule::find($id);

        if (!$rule) {
            return $this->notFoundResponse("Aturan harga tidak ditemukan");
        }

        $rule->delete();

        return $this->successResponse(null, "Aturan harga berhasil dihapus");
    }
}
