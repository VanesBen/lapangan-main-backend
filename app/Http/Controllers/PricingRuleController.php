<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePricingRuleRequest;
use App\Http\Requests\UpdatePricingRuleRequest;
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

    public function store(StorePricingRuleRequest $request): JsonResponse
    {
        $validated = $request->validated();

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

    public function update(UpdatePricingRuleRequest $request, string $id): JsonResponse
    {
        $rule = PricingRule::findOrFail($id);

        $rule->update($request->validated());

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
