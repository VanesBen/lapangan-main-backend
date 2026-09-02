<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PricingRuleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'             => $this->id,
            'courts_id'      => $this->courts_id,
            'day_type'       => $this->day_type,
            'start_hour'     => (int) $this->start_hour,
            'end_hour'       => (int) $this->end_hour,
            'price_per_hour' => (int) $this->price_per_hour,
            
            // Menampilkan info singkat court jika relasinya di-load
            'court'          => new CourtResource($this->whenLoaded('court')),
            
            'created_at'     => $this->created_at?->toIso8601String(),
            'updated_at'     => $this->updated_at?->toIso8601String(),
        ];
    }
}
