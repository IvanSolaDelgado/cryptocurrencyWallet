<?php

namespace App\Infrastructure\Controllers\BuyCoin;

use App\Application\Exceptions\CoinNotFoundException;
use App\Application\Exceptions\WalletNotFoundException;
use App\Application\Services\BuyCoinService;
use App\Infrastructure\Controllers\BuyCoin\BuyCoinRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller as BaseController;
use Symfony\Component\HttpFoundation\Response;

class BuyCoinController extends BaseController
{
    /**
     * @throws WalletNotFoundException
     * @throws CoinNotFoundException
     */
    public function __invoke(BuyCoinRequest $buyCoinRequest, BuyCoinService $buyCoinService): JsonResponse
    {
        $coinId = $buyCoinRequest->input('coin_id');
        $amountUsd = $buyCoinRequest->input('amount_usd');
        $walletId = $buyCoinRequest->input('wallet_id');

        $buyCoinService->execute($coinId, $walletId, $amountUsd);

        return response()->json(
            [
                'description' => 'successful operation'
            ],
            Response::HTTP_OK
        );
    }
}
