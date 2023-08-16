<?php

namespace App\Infrastructure\Controllers\OpenWallet;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;

class OpenWalletRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'user_id' => 'required|string',
        ];
    }

    /**
     * @param Validator $validator
     * @throws HttpResponseException
     */
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            response()->json([
                'error' => 'Bad Request',
                'message' => $validator->errors()->first(),
            ], JsonResponse::HTTP_BAD_REQUEST)
        );
    }
}
