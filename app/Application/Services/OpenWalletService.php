<?php

namespace App\Application\Services;

use App\Domain\DataSources\UserDataSource;
use App\Domain\DataSources\WalletDataSource;
use App\Infrastructure\Exceptions\UserNotFoundException;

class OpenWalletService
{
    private UserDataSource $userDataSource;
    private WalletDataSource $walletDataSource;

    public function __construct(UserDataSource $userDataSource, WalletDataSource $walletDataSource)
    {
        $this->userDataSource = $userDataSource;
        $this->walletDataSource = $walletDataSource;
    }

    /**
     * @throws UserNotFoundException
     */
    public function createWallet(string $userId): ?string
    {
        $user = $this->userDataSource->findById($userId);
        if (is_null($user)) {
            throw new UserNotFoundException('User not found');
        }

        return $this->walletDataSource->saveWalletInCache();
    }
}
