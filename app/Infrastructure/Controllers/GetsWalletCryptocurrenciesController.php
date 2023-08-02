<?php

namespace App\Infrastructure\Controllers;

use App\Application\Services\WalletCryptocurrenciesService;
use App\Validators\WalletIdValidator;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller as BaseController;

class GetsWalletCryptocurrenciesController extends BaseController
{
    public function __invoke(
        $wallet_id,
        WalletIdValidator $walletIdValidator,
        WalletCryptocurrenciesService $walletCryptocurrenciesService
    ): JsonResponse {
        if (!$walletIdValidator->validateWalletId($wallet_id)) {
            return response()->json(['error' => 'Bad Request',
                'message' => $walletIdValidator->getMessage($wallet_id)], Response::HTTP_BAD_REQUEST);
        }

        $walletCryptocurrencies = $walletCryptocurrenciesService->getWalletCryptocurrencies($wallet_id);

        if ($walletCryptocurrencies === null) {
            return response()->json(
                ['description' => 'A wallet with the specified ID was not found'],
                Response::HTTP_NOT_FOUND
            );
        }

        return response()->json($walletCryptocurrencies, Response::HTTP_OK);
    }
}
