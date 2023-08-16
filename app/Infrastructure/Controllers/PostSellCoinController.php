<?php

namespace App\Infrastructure\Controllers;

use App\Application\Exceptions\CoinNotFoundException;
use App\Application\Exceptions\WalletNotFoundException;
use App\Application\Services\SellCoinService;
use App\Http\Requests\SellCoinRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller as BaseController;

class PostSellCoinController extends BaseController
{
    /**
     * @throws CoinNotFoundException
     * @throws WalletNotFoundException
     */
    public function __invoke(SellCoinRequest $coinRequest, SellCoinService $sellCoinService): JsonResponse
    {
        $coinId = $coinRequest->input('coin_id');
        $walletId = $coinRequest->input('wallet_id');
        $amountUsd = $coinRequest->input('amount_usd');

        $sellCoinService->execute($coinId, $walletId, $amountUsd);

        return response()->json([
            'description' => 'successful operation',
        ], Response::HTTP_OK);
    }
}
