<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;

class BuyCoinRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            "coin_id" => "required|string",
            "wallet_id" => "required|string",
            "amount_usd" => "required|integer|min:0",
        ];
    }

    /**
     *
     * @param Validator $validator
     * @throws HttpResponseException
     */
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            response()->json(
                [
                    'error' => 'Bad Request',
                    'message' => $validator->errors()->first(),
                ],
                JsonResponse::HTTP_BAD_REQUEST
            )
        );
    }
}
