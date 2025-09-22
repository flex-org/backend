<?php

namespace App\Modules\Features\Requests;

use App\Modules\Features\Enums\FeatureType;
use App\Modules\Features\Models\Feature;
use Illuminate\Validation\Rules\Enum;
use Illuminate\Foundation\Http\FormRequest;

class FeatureStoreRequest extends FormRequest
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
            'icon' => 'required|string',
            'price' => 'required|min:1',
            'active' => 'nullable|boolean',
            'type' => ['required', 'string', new Enum(FeatureType::class)],
            'translations.en.name' => 'required|string|max:255',
            'translations.ar.name' => 'required|string|max:255',
            'translations.en.description' => 'nullable|string',
            'translations.ar.description' => 'nullable|string',
        ];
    }
}
