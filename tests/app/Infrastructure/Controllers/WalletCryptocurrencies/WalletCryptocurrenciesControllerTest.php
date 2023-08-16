<?php

namespace Tests\app\Infrastructure\Controllers\WalletCryptocurrencies;

use App\Domain\Coin;
use App\Domain\Wallet;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class WalletCryptocurrenciesControllerTest extends TestCase
{
    /**
     * @test
     */
    public function walletIdWasNotFoundIfWalletDoesNotExists()
    {
        $wallet = new Wallet('0');

        Cache::shouldReceive('has')->andReturn(false);

        $response = $this->get('api/wallet/' . $wallet->getWalletId());

        $response->assertStatus(Response::HTTP_NOT_FOUND);
        $response->assertExactJson(['description' => 'Wallet not found']);
    }

    /**
     * @test
     */
    public function returnsWalletCryptocurrenciesWhenWalletExists()
    {
        $walletId = '0';
        $walletCoins = [
            (new Coin('90', 'Bitcoin', 'BTC', 4, 26829.64))->getJsonData(),
            (new Coin('80', 'Ethereum', 'ETH', 10, 1830))->getJsonData(),
            (new Coin('518', 'Tether', 'USDT', 2, 1.00))->getJsonData(),
            (new Coin('2710', 'Binance Coin', 'BNB', 4, 30705))->getJsonData(),
        ];
        $wallet = new Wallet($walletId);

        Cache::shouldReceive('has')->andReturn(true);
        Cache::shouldReceive('get')
            ->with('wallet_' . $walletId)
            ->andReturn(['BuyTimeAccumulatedValue' => 10, 'coins' => $walletCoins]);

        $response = $this->get('api/wallet/' . $wallet->getWalletId());

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJson($walletCoins);
    }
}
