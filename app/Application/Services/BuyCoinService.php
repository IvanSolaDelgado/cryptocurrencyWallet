<?php

namespace App\Application\Services;

use App\Application\Exceptions\CoinNotFoundException;
use App\Application\Exceptions\WalletNotFoundException;
use App\Domain\DataSources\CoinDataSource;
use App\Domain\DataSources\WalletDataSource;

class BuyCoinService
{
    private CoinDataSource $coinDataSource;
    private WalletDataSource $walletDataSource;

    public function __construct(CoinDataSource $coinDataSource, WalletDataSource $walletDataSource)
    {
        $this->coinDataSource = $coinDataSource;
        $this->walletDataSource = $walletDataSource;
    }

    /**
     * @throws CoinNotFoundException
     * @throws WalletNotFoundException
     */
    public function execute(string $coinId, string $walletId, int $amountUsd): void
    {
        $coin = $this->coinDataSource->findById($coinId, $amountUsd);
        if (is_null($coin)) {
            throw new CoinNotFoundException();
        }

        $wallet = $this->walletDataSource->findById($walletId);
        if (is_null($wallet)) {
            throw new WalletNotFoundException();
        }

        $this->walletDataSource->insertCoinInWallet($wallet->getWalletId(), $coin);
    }
}
