<?php

namespace App\Infrastructure\Controllers;

use App\Domain\DataSources\CoinDataSource;
use App\Domain\DataSources\WalletDataSource;
use App\Validators\WalletIdValidator;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;

class GetsWalletBalanceController extends BaseController
{
    private WalletDataSource $walletDataSource;
    private CoinDataSource $coinDataSource;
    public function __construct(WalletDataSource $walletDataSource, CoinDataSource $coinDataSource)
    {
        $this->walletDataSource = $walletDataSource;
        $this->coinDataSource = $coinDataSource;
    }
    public function __invoke($wallet_id, WalletIdValidator $walletIdValidator): JsonResponse
    {
        if (!$walletIdValidator->validateWalletId($wallet_id)) {
            return response()->json(['error' => 'Bad Request',
                'message' => $walletIdValidator->getMessage($wallet_id)], Response::HTTP_BAD_REQUEST);
        }

        if (is_null($this->walletDataSource->findById($wallet_id))) {
            return response()->json([
                'description' => 'A wallet with the specified ID was not found'
            ], Response::HTTP_NOT_FOUND);
        }

        $walletArray = Cache::get('wallet_' . $wallet_id);
        $accumulatedSum = $walletArray['BuyTimeAccumulatedValue'];
        $totalValue = 0;

        foreach ($walletArray['coins'] as $coin) {
            $coinCurrentValue = $this->coinDataSource->getUsdValue($coin['coinId']);
            $totalValue += $coinCurrentValue * $coin['amount'];
        }

        $balance = $totalValue - $accumulatedSum;
        return response()->json(['balance_usd' => $balance], Response::HTTP_OK);
    }
}
