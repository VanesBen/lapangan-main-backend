<?php

namespace App\Http\Requests;

use App\Models\PricingRule;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class UpdatePricingRuleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'day_type'       => 'required|in:weekday,weekend,Weekday,Weekend',
            'start_hour'     => 'required|integer|min:0|max:23',
            'end_hour'       => 'required|integer|min:1|max:24|gt:start_hour',
            'price_per_hour' => 'required|integer|min:0',
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function ($validator) {
            if ($validator->failed()) {
                return;
            }

            // Ambil ID dari parameter route /prices/{price}
            $ruleId = $this->route('price'); 
            
            // Cari data rule saat ini untuk mendapatkan courts_id
            $currentRule = PricingRule::find($ruleId);
            if (!$currentRule) {
                return;
            }

            $courtsId  = $currentRule->courts_id;
            $dayType   = strtolower($this->input('day_type'));
            $startHour = (int) $this->input('start_hour');
            $endHour   = (int) $this->input('end_hour');

            // Cek overlap, kecualikan id yang sedang diedit
            $isOverlap = PricingRule::where('courts_id', $courtsId)
                ->whereRaw('LOWER(day_type) = ?', [$dayType])
                ->where('id', '!=', $currentRule->id)
                ->where(function ($query) use ($startHour, $endHour) {
                    $query->where('start_hour', '<', $endHour)
                          ->where('end_hour', '>', $startHour);
                })
                ->exists();

            if ($isOverlap) {
                $validator->errors()->add(
                    'start_hour',
                    "Rentang jam {$startHour}:00 - {$endHour}:00 bentrok dengan aturan {$dayType} lainnya."
                );
            }
        });
    }

    public function messages(): array
    {
        return [
            'end_hour.gt' => 'Jam selesai harus lebih besar dari jam mulai.',
        ];
    }
}
