<?php

namespace App\Infrastructure\Controllers;

use App\Application\Services\WalletCryptocurrenciesService;
use App\Infrastructure\Controllers\Validators\WalletIdValidator;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller as BaseController;

class WalletCryptocurrenciesController extends BaseController
{
    public function __invoke(
        $walletId,
        WalletIdValidator $walletIdValidator,
        WalletCryptocurrenciesService $walletCryptocurrenciesService
    ): JsonResponse {
        if (!$walletIdValidator->validateWalletId($walletId)) {
            return response()->json(
                [
                    'error' => 'Bad Request',
                    'message' => $walletIdValidator->getMessage($walletId)
                ],
                Response::HTTP_BAD_REQUEST
            );
        }

        $walletCryptocurrencies = $walletCryptocurrenciesService->getWalletCryptocurrencies($walletId);

        return response()->json($walletCryptocurrencies, Response::HTTP_OK);
    }
}
