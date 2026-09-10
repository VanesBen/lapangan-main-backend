<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class StoreCourtRequest extends FormRequest
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
            'name'                           => 'required|string|max:255',
            'category'                       => 'required|string|max:100',
            'location'                       => 'required|string|max:255',
            'photo'                          => 'required|string',
            'facilities'                     => 'nullable|string',
            'rules'                          => 'nullable|string',
            'description'                    => 'nullable|string',
            'is_active'                      => 'nullable',
            'pricing_rules'                  => 'required|array|min:1',
            'pricing_rules.*.day_type'       => 'required|in:weekday,weekend,Weekday,Weekend',
            'pricing_rules.*.start_hour'     => 'required|integer|min:0|max:23',
            'pricing_rules.*.end_hour'       => 'required|integer|min:1|max:24|gt:pricing_rules.*.start_hour',
            'pricing_rules.*.price_per_hour' => 'required|integer|min:0',
        ];
    }
    public function withValidator(Validator $validator): void
    {
        $validator->after(function ($validator) {
            if ($validator->failed()) {
                return;
            }

            $rules = $this->input('pricing_rules', []);
            $count = count($rules);

            // Deteksi tabrakan jam antar slot di dalam payload
            for ($i = 0; $i < $count; $i++) {
                for ($j = $i + 1; $j < $count; $j++) {
                    $a = $rules[$i];
                    $b = $rules[$j];

                    if (strtolower($a['day_type']) === strtolower($b['day_type'])) {
                        if ($a['start_hour'] < $b['end_hour'] && $a['end_hour'] > $b['start_hour']) {
                            $validator->errors()->add(
                                'pricing_rules',
                                "Terdapat jam yang bentrok pada tipe hari {$a['day_type']} ({$a['start_hour']}:00-{$a['end_hour']}:00 vs {$b['start_hour']}:00-{$b['end_hour']}:00)."
                            );
                            return;
                        }
                    }
                }
            }
        });
    }

    public function messages(): array
    {
        return [
            'pricing_rules.required' => 'Minimal harus menambahkan 1 aturan jam & tarif sewa.',
            'pricing_rules.*.end_hour.gt' => 'Jam selesai harus lebih besar dari jam mulai.',
        ];
    }
}
