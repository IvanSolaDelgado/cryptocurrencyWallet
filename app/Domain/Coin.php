<?php

namespace App\Domain;

class Coin
{
    private string $coinId;
    private string $name;
    private string $symbol;
    private float $amount;
    private float $valueUsd;

    public function __construct(
        string $coinId,
        string $name,
        string $symbol,
        float $amount,
        float $valueUsd
    ) {
        $this->coinId = $coinId;
        $this->name = $name;
        $this->symbol = $symbol;
        $this->amount = $amount;
        $this->valueUsd = $valueUsd;
    }

    public function getJsonData(): array
    {
        $coinData = get_object_vars($this);
        return $coinData;
    }

    public function getId(): string
    {
        return $this->coinId;
    }

    public function getAmount(): float
    {
        return $this->amount;
    }

    public function setAmount(float $amount): void
    {
        $this->amount = $amount;
    }

    public function getValueUsd(): float
    {
        return $this->valueUsd;
    }
}
