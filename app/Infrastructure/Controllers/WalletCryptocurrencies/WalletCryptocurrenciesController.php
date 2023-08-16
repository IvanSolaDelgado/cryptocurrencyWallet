<?php

namespace App\Infrastructure\Controllers\WalletCryptocurrencies;

use App\Application\Services\WalletCryptocurrenciesService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller as BaseController;

class WalletCryptocurrenciesController extends BaseController
{
    public function __invoke(
        $walletId,
        CryptocurrenciesWalletIdValidator $walletIdValidator,
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

        $walletCryptocurrencies = $walletCryptocurrenciesService->execute($walletId);

        return response()->json($walletCryptocurrencies, Response::HTTP_OK);
    }
}
