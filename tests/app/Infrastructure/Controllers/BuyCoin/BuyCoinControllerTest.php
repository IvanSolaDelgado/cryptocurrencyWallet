<?php

namespace Tests\app\Infrastructure\Controllers\BuyCoin;

use App\Domain\Coin;
use App\Domain\DataSources\CoinDataSource;
use Illuminate\Support\Facades\Cache;
use Mockery;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class BuyCoinControllerTest extends TestCase
{
    private CoinDataSource $coinDataSource;

    protected function setUp(): void
    {
        parent::setUp();
        $this->coinDataSource = Mockery::mock(CoinDataSource::class);
        $this->app->bind(CoinDataSource::class, function () {
            return $this->coinDataSource;
        });
    }

    /**
     * @test
     */
    public function coinIdWasNotFoundWhenCoinDoesNotExist()
    {
        $this->coinDataSource
            ->expects("findById")
            ->with("coin_id_value", "1")
            ->andReturn(null);

        $response = $this->post(
            'api/coin/buy',
            [
                "coin_id" => "coin_id_value",
                "wallet_id" => "wallet_id_value",
                "amount_usd" => 1
            ]
        );

        $response->assertStatus(Response::HTTP_NOT_FOUND);
        $response->assertExactJson(
            [
                'description' => 'Coin not found'
            ]
        );
    }

    /**
     * @test
     */
    public function coinIdWasNotFoundWhenWalletDoesNotExist()
    {
        $this->coinDataSource
            ->expects("findById")
            ->with("coin_id_value", "1")
            ->andReturn(new Coin(
                "coin_id_value",
                "name_value",
                "symbol_value",
                1,
                1
            ));
        Cache::shouldReceive('has')->once()->with('wallet_0')->andReturn(false);

        $response = $this->post(
            'api/coin/buy',
            [
                "coin_id" => "coin_id_value",
                "wallet_id" => "0",
                "amount_usd" => 1
            ]
        );

        $response->assertStatus(Response::HTTP_NOT_FOUND);
        $response->assertExactJson(
            [
                'description' => 'Wallet not found'
            ]
        );
    }

    /**
     * @test
     */
    public function successfullyBuyOperation()
    {
        $coin = new Coin("coin_id_value", "name_value", "symbol_value", 1, 1);
        $this->coinDataSource
            ->expects("findById")
            ->with("coin_id_value", "1")
            ->andReturn($coin);
        Cache::shouldReceive('has')
            ->once()
            ->with('wallet_0')
            ->andReturn(true);
        Cache::shouldReceive('has')
            ->once()
            ->with('wallet_0')
            ->andReturn(true);
        Cache::shouldReceive('get')
            ->once()
            ->with("wallet_0")
            ->andReturn(
                [
                'walletId' => '0',
                'BuyTimeAccumulatedValue' => 0,
                'coins' => [],
                ]
            );
        Cache::shouldReceive('put')
            ->with("wallet_0", Mockery::type('array'));

        $response = $this->post(
            'api/coin/buy',
            [
                "coin_id" => "coin_id_value",
                "wallet_id" => "0",
                "amount_usd" => 1
            ]
        );

        $response->assertStatus(Response::HTTP_OK);
        $response->assertExactJson(
            [
                'description' => 'successful operation'
            ]
        );
    }
}
