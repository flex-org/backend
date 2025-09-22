<?php

namespace App\Modules\Utilities\Requests;

use App\Http\Requests\AbstractApiRequest;

class LoginRequest extends AbstractApiRequest
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

        ];
    }

    public function messages()
    {
        return [
            'email.exists' => 'the credentials  doesn\'t match our records',
        ];
    }
}
