<?php

namespace App\Application\Services;

use App\Application\DataSources\UserDataSource;
use App\Application\DataSources\WalletDataSource;

class OpenWalletService
{
    private UserDataSource $userDataSource;
    private WalletDataSource $walletDataSource;

    public function __construct(UserDataSource $userDataSource, WalletDataSource $walletDataSource)
    {
        $this->userDataSource = $userDataSource;
        $this->walletDataSource = $walletDataSource;
    }

    public function openWallet(string $userId): ?string
    {
        $user = $this->userDataSource->findById($userId);
        if (is_null($user)) {
            return null;
        }

        return $this->walletDataSource->saveWalletInCache();
    }
}
