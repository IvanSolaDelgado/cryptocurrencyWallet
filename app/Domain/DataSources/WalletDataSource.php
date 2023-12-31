<?php

namespace App\Domain\DataSources;

use App\Domain\Coin;
use App\Domain\Wallet;

interface WalletDataSource
{
    public function findById(string $walletId): ?Wallet;

    public function insertCoinInWallet(string $walletId, Coin $coin): void;

    public function sellCoinFromWallet(string $walletId, Coin $coin, string $amountUsd): void;

    public function saveWalletInCache(): ?string;

    public function getWalletById(string $walletId);
}
