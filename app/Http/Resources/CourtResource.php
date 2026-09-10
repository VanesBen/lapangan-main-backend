<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CourtResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'photo' => $this->photo,
            'description' => $this->description,
            'is_active' => $this->is_active,
            'facilities' => $this->facilities,
            'rules' => $this->rules,
            'location' => $this->location,
            'category' => $this->category,
            'prices' => PricingRuleResource::collection($this->whenLoaded('pricingRules'))
        ];
        
    }
}
