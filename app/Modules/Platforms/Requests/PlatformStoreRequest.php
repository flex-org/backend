<?php

namespace App\Modules\Platforms\Requests;

use Illuminate\Validation\Rules\Enum;
use Illuminate\Foundation\Http\FormRequest;
use App\Modules\Utilities\enums\BillingCycle;
use App\Modules\Platforms\Enums\PlatformSellingSystem;

class PlatformStoreRequest extends FormRequest
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
            'billing_cycle' => ['required', 'string', new Enum(BillingCycle::class)],
            'domain' => 'required|string|unique:platforms',
            'features' => 'nullable|array',
            'features.*' => 'required|integer|exists:features,id',
            'selling_system' => ['required','string', new Enum(PlatformSellingSystem::class)]
        ];
    }
}
