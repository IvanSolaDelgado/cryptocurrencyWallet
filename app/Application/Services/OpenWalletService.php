<?php

namespace App\Application\Services;

use App\Application\Exceptions\UserNotFoundException;
use App\Domain\DataSources\UserDataSource;
use App\Domain\DataSources\WalletDataSource;

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
            throw new UserNotFoundException();
        }

        return $this->walletDataSource->saveWalletInCache();
    }
}
