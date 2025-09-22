<?php

namespace App\Facades\FacadesLogic;

use Illuminate\Http\Response;

class ApiResponseLogic
{
    /**
     * @param $info
     * @param $message
     * @param $code
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Foundation\Application|Response
     */
    function apiFormat($info, $message = null, $code = Response::HTTP_OK)
    {
        $response = [
            'success' => ($code >= 200 && $code < 300),
        ];

        if ($message) {
            $response['message'] = $message;
        }

        if ($info) {
            $key = key($info);
            $response[$key] = $info[$key];
        }

        return response($response, $code);
    }

    public function notFound($message = null)
    {
        return $this->apiFormat(
            null,
            $message ?? __('apiMessages.not_found'),
            Response::HTTP_NOT_FOUND
        );
    }

    public function serverError($message = null)
    {
        return $this->apiFormat(
            null,
            $message ?? __('apiMessages.server_error'),
            Response::HTTP_INTERNAL_SERVER_ERROR
        );
    }

    public function validationError($errors, $message = null)
    {
        return $this->failed(
            $errors,
            $message ?? __('apiMessages.validation_error'),
            Response::HTTP_UNPROCESSABLE_ENTITY
        );
    }

    public function unauthorized($message = null, $code = Response::HTTP_UNAUTHORIZED)
    {
        return $this->message($message ?? __('apiMessages.unauthorized'), $code);
    }

    public function forbidden($message = null, $code = Response::HTTP_FORBIDDEN)
    {
        return $this->message($message ?? __('apiMessages.forbidden'), $code);
    }

    public function failed($errors, $message, $code)
    {
        $errors = $errors ? ['errors' => $errors] : null;
        return $this->apiFormat(
            $errors,
            $message,
            $code
        );
    }

    public function success($data, $message = null, $code = Response::HTTP_OK)
    {
        return $this->apiFormat(
            ['data' => $data],
            $message,
            $code
        );
    }

    public function message($message, $code = Response::HTTP_OK)
    {
        return $this->apiFormat(
            null,
            $message,
            $code
        );
    }

    public function created($message = null)
    {
        return $this->message(
            $message ?? __('apiMessages.created'),
            Response::HTTP_CREATED
        );
    }

    public function updated($message = null)
    {
        return $this->message(
            $message ?? __('apiMessages.updated')
        );
    }

    public function deleted($message = null)
    {
        return $this->message($message ?? __('apiMessages.deleted'), Response::HTTP_NO_CONTENT);
    }
}

