<?php

namespace Tests\app\Infrastructure\Controller;

use App\Domain\Coin;
use App\Domain\DataSources\CoinDataSource;
use Illuminate\Support\Facades\Cache;
use Mockery;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class PostBuyCoinControllerTest extends TestCase
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
    public function doesNotBuyCoinWhenCoinDoesNotExist()
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

        $response->assertStatus(Response::HTTP_BAD_REQUEST);
        $response->assertExactJson(
            [
                'description' => 'Coin not found'
            ]
        );
    }

    /**
     * @test
     */
    public function doesNotBuyCoinWhenWalletDoesNotExist()
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

        $response->assertStatus(Response::HTTP_BAD_REQUEST);
        $response->assertExactJson(
            [
                'description' => 'Wallet not found'
            ]
        );
    }

    /**
     * @test
     */
    public function buysCoinWhenCoinAndWalletExist()
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
        Cache::shouldReceive('has')->once()->with('wallet_0')->andReturn(true);
        Cache::shouldReceive('has')->once()->with('wallet_0')->andReturn(true);
        Cache::shouldReceive('get')->once()->with("wallet_0")->andReturn(
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
