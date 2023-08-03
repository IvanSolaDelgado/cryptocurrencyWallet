<?php

namespace Tests\app\Infrastructure\Controller;

use App\Domain\DataSources\CoinDataSource;
use App\Domain\DataSources\WalletDataSource;
use App\Domain\Wallet;
use App\Infrastructure\ApiServices\CoinloreApiService;
use App\Infrastructure\Persistence\ApiCoinDataSource;
use Illuminate\Support\Facades\Cache;
use Mockery;
use Tests\TestCase;

class GetsWalletBalanceControllerTest extends TestCase
{
    /**
     * @test
     */
    public function throwsErrorWhenWalletIdNotFound()
    {
        $walletOne = new Wallet('0');

        Cache::shouldReceive('has')->andReturn(false);

        $response = $this->get('api/wallet/' . $walletOne->getWalletId() . '/balance');

        $response->assertNotFound();
        $response->assertExactJson(['description' => 'A wallet with the specified ID was not found']);
    }

    /**
     * @test
     */
    public function getsWalletBalanceWhenWalletIdFound()
    {
        $coinloreApiService = Mockery::mock(CoinloreApiService::class);
        $coinDataSource = new ApiCoinDataSource($coinloreApiService);
        $this->app->bind(CoinDataSource::class, function () use ($coinDataSource) {
            return $coinDataSource;
        });
        $walletId = '0';
        $wallet = new Wallet($walletId);
        $coinId = 'someCoinId';
        $coinAmount = 5;
        $coinCurrentValue = 20;
        $coinBuyTimeAccumulatedValue = 50;

        Cache::shouldReceive('has')->andReturn(true);
        Cache::shouldReceive('get')
            ->with('wallet_' . $walletId)
            ->andReturn(['BuyTimeAccumulatedValue' => $coinBuyTimeAccumulatedValue,
                'coins' => [['coinId' => $coinId, 'amount' => $coinAmount]]]);
        $coinloreApiService->shouldReceive("getCoinloreData")
            ->with($coinId)
            ->andReturn('[{"id": "90", "name": "Bitcoin", "symbol": "BTC", "price_usd": "20"}]');

        $response = $this->get('api/wallet/' . $wallet->getWalletId() . '/balance');

        $expectedBalance = ($coinCurrentValue * $coinAmount) - $coinBuyTimeAccumulatedValue;
        $response->assertOk();
        $response->assertJson(['balance_usd' => $expectedBalance]);
    }
}
