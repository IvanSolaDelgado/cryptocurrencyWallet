<?php

namespace App\Application\Services;

use App\Application\Exceptions\WalletNotFoundException;
use App\Domain\DataSources\CoinDataSource;
use App\Domain\DataSources\WalletDataSource;

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
    public function execute($walletId): float
    {
        if (is_null($this->walletDataSource->findById($walletId))) {
            throw new WalletNotFoundException();
        }

        $wallet = $this->walletDataSource->getWalletById($walletId);
        $accumulatedSum = $wallet['BuyTimeAccumulatedValue'];
        $totalValue = 0;

        foreach ($wallet['coins'] as $coin) {
            $coinCurrentValue = $this->coinDataSource->getUsdValue($coin['coinId']);
            $totalValue += $coinCurrentValue * $coin['amount'];
        }

        return $totalValue - $accumulatedSum;
    }
}
