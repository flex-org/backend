<?php

namespace App\Modules\Plans\Requests;

use Illuminate\Validation\Rules\Enum;
use Illuminate\Foundation\Http\FormRequest;
use App\Modules\Plans\Enums\PlanBillingCycle;

class PlanUpdateRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'active' => 'nullable|boolean',
            
            'pricing' => 'nullable|array',
            'pricing.*.billing_cycle' => ['required', 'string', new Enum(PlanBillingCycle::class)],
            'pricing.*.months' => ['required', 'integer', 'max:12'],
            'pricing.*.price' => ['required', 'integer'],
            'pricing.*.discount' => ['nullable', 'integer'],
            
            'translations.en.description' => 'nullable|string',
            'translations.ar.description' => 'nullable|string',
            'translations.en.points' => 'required|array',
            'translations.en.points.*' => 'required|string',
            'translations.ar.points' => 'required|array',
            'translations.ar.points.*' => 'required|string',
        ];
    }
}
