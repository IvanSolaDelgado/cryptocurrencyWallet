<?php

namespace App\Infrastructure\Controllers;

use App\Domain\DataSources\CoinDataSource;
use App\Domain\DataSources\WalletDataSource;
use App\Http\Requests\BuyCoinRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller as BaseController;

class PostBuyCoinController extends BaseController
{
    private CoinDataSource $coinDataSource;
    private WalletDataSource $walletDataSource;

    public function __construct(CoinDataSource $coinDataSource, WalletDataSource $walletDataSource)
    {
        $this->coinDataSource = $coinDataSource;
        $this->walletDataSource = $walletDataSource;
    }

    public function __invoke(BuyCoinRequest $buyCoinRequest): JsonResponse
    {
        $coinId = $buyCoinRequest->input('coin_id');
        $amountUsd = $buyCoinRequest->input('amount_usd');
        $walletId = $buyCoinRequest->input('wallet_id');

        $coin = $this->coinDataSource->findById($coinId, $amountUsd);
        if (is_null($coin)) {
            return response()->json(
                [
                    'description' => 'A coin with the specified ID was not found.'
                ],
                Response::HTTP_NOT_FOUND
            );
        }

        $wallet = $this->walletDataSource->findById($walletId);
        if (is_null($wallet)) {
            return response()->json(
                [
                    'description' => 'A wallet with the specified ID was not found'
                ],
                Response::HTTP_NOT_FOUND
            );
        }

        $this->walletDataSource->insertCoinInWallet($wallet->getWalletId(), $coin);

        return response()->json(
            [
                'description' => 'successful operation'
            ],
            Response::HTTP_OK
        );
    }
}
