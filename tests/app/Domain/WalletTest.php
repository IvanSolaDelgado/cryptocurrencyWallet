<?php

namespace Tests\app\Domain;

use App\Domain\Coin;
use App\Domain\Wallet;
use Tests\TestCase;

class WalletTest extends TestCase
{
    /**
     * @test
     */
    public function serializesWallet()
    {
        $firstCoin = new Coin('coin_id_1', 'Bitcoin', 'BTC', 1.0, 50000.0);
        $secondCoin = new Coin('coin_id_2', 'Ethereum', 'ETH', 10.0, 3000.0);
        $wallet = new Wallet('wallet_id');
        $wallet->addCoin($firstCoin);
        $wallet->addCoin($secondCoin);
        $serializedFirstCoin = [
            'coinId' => 'coin_id_1',
            'name' => 'Bitcoin',
            'symbol' => 'BTC',
            'amount' => 1.0,
            'valueUsd' => 50000.0
        ];
        $serializedSecondCoin = [
            'coinId' => 'coin_id_2',
            'name' => 'Ethereum',
            'symbol' => 'ETH',
            'amount' => 10.0,
            'valueUsd' => 3000.0
        ];

        $serializedWallet = $wallet->serializeData();

        $this->assertEquals('wallet_id', $serializedWallet['walletId']);
        $this->assertCount(2, $serializedWallet['coins']);
        $this->assertEquals($serializedFirstCoin, $serializedWallet['coins'][0]);
        $this->assertEquals($serializedSecondCoin, $serializedWallet['coins'][1]);
    }
}
