<?php

namespace App\Modules\V1\Initialization\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SavingPlatformSystemsRequest extends FormRequest
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
            'selling_system' => ['required','array'],
            'selling_system.*' => ['required', 'integer', 'exists:selling_systems,id'],
            'storage' => 'required|integer|min:20|max:1024',
            'capacity' => 'required|integer|min:100,max:10000',
            'mobile_app' => 'required|boolean',
        ];
    }
}
