<?php

namespace App\Http\Requests;

use App\Facades\ApiResponse;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;

Abstract class AbstractApiRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    Abstract public function authorize();


    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    Abstract public function rules();

    /**
     * Handle a failed validation attempt.
     *
     * @param  \Illuminate\Contracts\Validation\Validator  $validator
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function failedValidation(Validator $validator)
    {
        $errors = (new ValidationException($validator))->errors();

        if (!empty($errors)) {
            $formedErrors = [];
            foreach ($errors as $filed => $message) {
                $formedErrors[$filed] = $message[0];
            }
            throw new HttpResponseException(
                ApiResponse::validationError($formedErrors)
            );
        }
    }
}
