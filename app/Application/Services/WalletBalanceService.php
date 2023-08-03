<?php

namespace App\Application\Services;

use App\Application\Exceptions\WalletNotFoundException;
use App\Domain\DataSources\CoinDataSource;
use App\Domain\DataSources\WalletDataSource;
use Illuminate\Support\Facades\Cache;

class WalletBalanceService
{
    private WalletDataSource $walletDataSource;
    private CoinDataSource $coinDataSource;
    public function __construct(WalletDataSource $walletDataSource, CoinDataSource $coinDataSource)
    {
        $this->walletDataSource = $walletDataSource;
        $this->coinDataSource = $coinDataSource;
    }

    /**
     * @throws WalletNotFoundException
     */
    public function getsBalance($walletId)
    {
        if (is_null($this->walletDataSource->findById($walletId))) {
            throw new WalletNotFoundException();
        }

        $walletArray = $this->walletDataSource->getWalletById($walletId);
        $accumulatedSum = $walletArray['BuyTimeAccumulatedValue'];
        $totalValue = 0;

        foreach ($walletArray['coins'] as $coin) {
            $coinCurrentValue = $this->coinDataSource->getUsdValue($coin['coinId']);
            $totalValue += $coinCurrentValue * $coin['amount'];
        }

        return $totalValue - $accumulatedSum;
    }
}
