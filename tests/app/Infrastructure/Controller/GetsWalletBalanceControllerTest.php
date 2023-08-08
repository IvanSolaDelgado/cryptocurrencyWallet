<?php

namespace Tests\app\Infrastructure\Controller;

use App\Domain\DataSources\CoinDataSource;
use App\Domain\Wallet;
use App\Infrastructure\ApiServices\CoinloreApiService;
use App\Infrastructure\Persistence\ApiCoinDataSource;
use Illuminate\Support\Facades\Cache;
use Mockery;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class GetsWalletBalanceControllerTest extends TestCase
{
    private CoinloreApiService $coinloreApiService;
    private ApiCoinDataSource $apiCoinDataSource;

    protected function setUp(): void
    {
        parent::setUp();

        $this->coinloreApiService = Mockery::mock(CoinloreApiService::class);
        $this->apiCoinDataSource = new ApiCoinDataSource($this->coinloreApiService);
        $this->app->bind(CoinDataSource::class, function () {
            return $this->apiCoinDataSource;
        });
    }


    /**
     * @test
     */
    public function walletIdWasNotFoundIfWalletDoesNotExist()
    {
        $walletOne = new Wallet('0');

        Cache::shouldReceive('has')->andReturn(false);

        $response = $this->get('api/wallet/' . $walletOne->getWalletId() . '/balance');

        $response->assertStatus(Response::HTTP_BAD_REQUEST);
        $response->assertExactJson(['description' => 'Wallet not found']);
    }

    /**
     * @test
     */
    public function getsWalletBalanceWhenWalletIdFound()
    {
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
        $this->coinloreApiService->shouldReceive("getCoinloreData")
            ->with($coinId)
            ->andReturn('[{"id": "90", "name": "Bitcoin", "symbol": "BTC", "price_usd": "20"}]');

        $response = $this->get('api/wallet/' . $wallet->getWalletId() . '/balance');
        $expectedBalance = ($coinCurrentValue * $coinAmount) - $coinBuyTimeAccumulatedValue;

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJson(['balance_usd' => $expectedBalance]);
    }
}
