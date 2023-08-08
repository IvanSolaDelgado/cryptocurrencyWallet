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
    public function execute(string $userId): ?string
    {
        $user = $this->userDataSource->findById($userId);
        if ($user === null) {
            throw new UserNotFoundException();
        }

        $walletId = $this->walletDataSource->saveWalletInCache();
        if ($walletId === null) {
            return 'Cache is full';
        }

        return $walletId;
    }
}
