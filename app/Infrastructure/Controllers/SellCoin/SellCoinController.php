<?php

namespace App\Infrastructure\Controllers\SellCoin;

use App\Application\Exceptions\CoinNotFoundException;
use App\Application\Exceptions\WalletNotFoundException;
use App\Application\Services\SellCoinService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller as BaseController;

class SellCoinController extends BaseController
{
    /**
     * @throws CoinNotFoundException
     * @throws WalletNotFoundException
     */
    public function __invoke(SellCoinRequest $sellCoinRequest, SellCoinService $sellCoinService): JsonResponse
    {
        $coinId = $sellCoinRequest->input('coin_id');
        $walletId = $sellCoinRequest->input('wallet_id');
        $amountUsd = $sellCoinRequest->input('amount_usd');

        $sellCoinService->execute($coinId, $walletId, $amountUsd);

        return response()->json([
            'description' => 'successful operation',
        ], Response::HTTP_OK);
    }
}
