<?php

namespace App\Infrastructure\Persistence;

use App\Domain\Coin;
use App\Domain\DataSources\CoinDataSource;
use App\Infrastructure\ApiServices\CoinloreApiService;

class ApiCoinDataSource implements CoinDataSource
{
    private CoinloreApiService $coinloreApiService;

    public function __construct(CoinloreApiService $coinloreApiService)
    {
        $this->coinloreApiService = $coinloreApiService;
    }

    public function findById(string $coinId, string $amountUsd): ?Coin
    {
        $response = $this->coinloreApiService->getCoinloreData($coinId);

        if ($response) {
            $coin_data = json_decode($response, true)[0];
            return new Coin(
                $coin_data["id"],
                $coin_data["name"],
                $coin_data["symbol"],
                floatval($amountUsd) / floatval($coin_data["price_usd"]),
                $coin_data["price_usd"]
            );
        }
        return null;
    }

    public function getUsdValue(string $coinId): ?float
    {
        $response = $this->coinloreApiService->getCoinloreData($coinId);

        if ($response) {
            $coin_data = json_decode($response, true)[0];
            return floatval($coin_data["price_usd"]);
        }
        return null;
    }
}
