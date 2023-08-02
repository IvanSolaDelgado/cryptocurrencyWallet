<?php

namespace App\Application\Services;

use App\Application\Exceptions\WalletNotFoundException;
use App\Domain\DataSources\WalletDataSource;
use Illuminate\Support\Facades\Cache;

class WalletCryptocurrenciesService
{
    private WalletDataSource $walletDataSource;

    public function __construct(WalletDataSource $walletDataSource)
    {
        $this->walletDataSource = $walletDataSource;
    }

    /**
     * @throws WalletNotFoundException
     */
    public function getWalletCryptocurrencies($wallet_id): array
    {
        $walletId = $this->walletDataSource->findById($wallet_id);
        if ($walletId === null) {
            throw new WalletNotFoundException();
        }

        $wallet = Cache::get('wallet_' . $wallet_id);

        return $wallet['coins'];
    }
}
