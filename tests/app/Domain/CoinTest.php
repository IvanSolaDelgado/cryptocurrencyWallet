<?php

namespace Tests\app\Domain;

use App\Domain\Coin;
use Tests\TestCase;

class CoinTest extends TestCase
{
    /**
     * @test
     */
    public function getsDataOfACoin()
    {
        $coin = new Coin('coin_id_1', 'Bitcoin', 'BTC', 1.0, 50000.0);
        $expectedCoinData = [
            'coinId' => 'coin_id_1',
            'name' => 'Bitcoin',
            'symbol' => 'BTC',
            'amount' => 1.0,
            'valueUsd' => 50000.0
        ];

        $coinData = $coin->getJsonData();

        $this->assertEquals($expectedCoinData, $coinData);
    }
}
