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
    public function execute($walletId): array
    {
        $wallet = $this->walletDataSource->findById($walletId);
        if (is_null($wallet)) {
            throw new WalletNotFoundException();
        }

        $walletData = $this->walletDataSource->getWalletById($walletId);

        return $walletData['coins'];
    }
}
