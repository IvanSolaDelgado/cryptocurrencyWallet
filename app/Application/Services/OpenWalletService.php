<?php

namespace App\Application\Services;

use App\Domain\DataSources\UserDataSource;
use App\Domain\DataSources\WalletDataSource;
use Exception;

class OpenWalletService
{
    private UserDataSource $userDataSource;
    private WalletDataSource $walletDataSource;

    public function __construct(UserDataSource $userDataSource, WalletDataSource $walletDataSource)
    {
        $this->userDataSource = $userDataSource;
        $this->walletDataSource = $walletDataSource;
    }

    public function createWallet(string $userId): ?string
    {
        try {
            $user = $this->userDataSource->findById($userId);
            if (is_null($user)) {
                throw new Exception('User not found.');
            }

            return $this->walletDataSource->saveWalletInCache();
        } catch (Exception $exception) {
            return null;
        }
    }
}
