<?php

namespace App\Application\Services;

use App\Application\Exceptions\UserNotFoundException;
use App\Application\Exceptions\CacheFullException;
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
     * @throws CacheFullException
     */
    public function createWallet(string $userId): ?string
    {
        $user = $this->userDataSource->findById($userId);
        if (is_null($user)) {
            throw new UserNotFoundException();
        }

        $walletId = $this->walletDataSource->saveWalletInCache();
        if (is_null($walletId)) {
            throw new CacheFullException();
        }

        return $walletId;
    }
}
