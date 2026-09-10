<?php

namespace App\Http\Requests;

use App\Models\PricingRule;
use Illuminate\Validation\Validator;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StorePricingRuleRequest extends FormRequest
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
            'courts_id'      => 'required|exists:courts,id',
            'day_type'       => 'required|in:weekday,weekend',
            'start_hour'     => 'required|integer|min:0|max:23',
            'end_hour'       => 'required|integer|min:1|max:24|gt:start_hour',
            'price_per_hour' => 'required|integer|min:0',
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function ($validator) {
            // Jika validasi format dasar di rules() gagal, lewati pengecekan database
            if ($validator->failed()) {
                return;
            }

            $courtsId  = $this->input('courts_id');
            $dayType   = $this->input('day_type');
            $startHour = (int) $this->input('start_hour');
            $endHour   = (int) $this->input('end_hour');

            // Logika Overlap: (startA < endB) AND (endA > startB)
            $isOverlap = PricingRule::where('courts_id', $courtsId)
                ->where('day_type', $dayType)
                ->where(function ($query) use ($startHour, $endHour) {
                    $query->where('start_hour', '<', $endHour)
                          ->where('end_hour', '>', $startHour);
                })
                ->exists();

            if ($isOverlap) {
                $validator->errors()->add(
                    'start_hour',
                    "Rentang jam {$startHour}:00 - {$endHour}:00 bentrok dengan aturan {$dayType} yang sudah ada."
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
