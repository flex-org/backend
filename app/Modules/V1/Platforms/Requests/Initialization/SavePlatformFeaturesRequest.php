<?php

namespace App\Modules\V1\Platforms\Requests\Initialization;

use Illuminate\Foundation\Http\FormRequest;

class SavePlatformFeaturesRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return (is_null($this->user()->platform));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'features' => 'nullable|array',
            'features.*' => 'required|integer|exists:features,id',
        ];
    }
}
