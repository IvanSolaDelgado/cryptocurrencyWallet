<?php

namespace App\Infrastructure\Controllers;

use App\Domain\DataSources\CoinDataSource;
use App\Domain\DataSources\WalletDataSource;
use App\Http\Requests\CoinRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller as BaseController;

class PostSellCoinController extends BaseController
{
    private CoinDataSource $coinDataSource;
    private WalletDataSource $walletDataSource;

    public function __construct(CoinDataSource $coinDataSource, WalletDataSource $walletDataSource)
    {
        $this->coinDataSource = $coinDataSource;
        $this->walletDataSource = $walletDataSource;
    }

    public function __invoke(CoinRequest $coinRequest): JsonResponse
    {
        $coinId = $coinRequest->input('coin_id');
        $walletId = $coinRequest->input('wallet_id');
        $amountUsd = $coinRequest->input('amount_usd');


        $coin = $this->coinDataSource->findById($coinId, $amountUsd);
        if (is_null($coin)) {
            return response()->json([
                'description' => 'A coin with the specified ID was not found'
            ], Response::HTTP_NOT_FOUND);
        }

        $wallet = $this->walletDataSource->findById($walletId);
        if (is_null($wallet)) {
            return response()->json([
                'description' => 'A wallet with the specified ID was not found'
            ], Response::HTTP_NOT_FOUND);
        }

        $this->walletDataSource->sellCoinFromWallet(
            $wallet->getWalletId(),
            $coin,
            $amountUsd
        );
        return response()->json([
            'description' => 'successful operation',
        ], Response::HTTP_OK);
    }
}
