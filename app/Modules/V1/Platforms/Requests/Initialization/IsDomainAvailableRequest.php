<?php

namespace App\Modules\V1\Platforms\Requests\Initialization;

use Illuminate\Foundation\Http\FormRequest;

class IsDomainAvailableRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return (bool)($this->user()->platformInitialization);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'domain' => 'required|string|max:100|unique:platforms,domain',
        ];
    }
}
