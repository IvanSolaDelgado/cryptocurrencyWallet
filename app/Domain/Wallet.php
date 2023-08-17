<?php

namespace App\Domain;

class Wallet
{
    private string $walletId;
    private array $coins;

    public function __construct(string $walletId)
    {
        $this->walletId = $walletId;
        $this->coins = [];
    }

    public function getWalletId(): string
    {
        return $this->walletId;
    }

    public function serializeData(): array
    {
        $attributes = get_object_vars($this);
        foreach ($attributes as &$attribute) {
            if (is_array($attribute)) {
                foreach ($attribute as &$coin) {
                    $coin = $coin->getJsonData();
                }
            }
        }
        return $attributes;
    }
}
