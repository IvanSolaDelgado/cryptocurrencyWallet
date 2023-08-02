<?php

namespace App\Application\Services;

use App\Domain\DataSources\WalletDataSource;
use Illuminate\Support\Facades\Cache;

class WalletCryptocurrenciesService
{
    private WalletDataSource $walletDataSource;

    public function __construct(WalletDataSource $walletDataSource)
    {
        $this->walletDataSource = $walletDataSource;
    }

    public function getWalletCryptocurrencies($wallet_id)
    {
        $wallet = $this->walletDataSource->findById($wallet_id);
        if ($wallet === null) {
            return null;
        }

        $walletArray = Cache::get('wallet_' . $wallet_id);

        return [$walletArray['coins']];
    }
}
