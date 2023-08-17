<?php

namespace Tests\app\Infrastructure\DataSources;

use App\Domain\Coin;
use App\Infrastructure\ApiServices\CoinloreApiService;
use App\Infrastructure\Persistence\ApiCoinDataSource;
use Mockery;
use PHPUnit\Framework\TestCase;

class CoinDataSourceTest extends TestCase
{
    private CoinloreApiService $coinloreApiService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->coinloreApiService = Mockery::mock(CoinloreApiService::class);
    }

    /**
     * @test
     */
    public function doesNotFindCoinWhenCoinIdDoesNotExist()
    {
        $coinDataSource = new ApiCoinDataSource($this->coinloreApiService);
        $coinId = 'invalid_coin_id';
        $amountUsd = '500';

        $this->coinloreApiService
            ->shouldReceive('getCoinloreData')
            ->with($coinId)
            ->andReturn(null);

        $coin = $coinDataSource->findById($coinId, $amountUsd);

        $this->assertNull($coin);
    }

    /**
     * @test
     */
    public function findCoinWhenCoinIdExists()
    {
        $coinDataSource = new ApiCoinDataSource($this->coinloreApiService);
        $coinId = '90';
        $amountUsd = '500';
        $response =  '[{"id": "90", "name": "Bitcoin", "symbol": "BTC", "price_usd": "123.45"}]';

        $this->coinloreApiService
            ->shouldReceive('getCoinloreData')
            ->with($coinId)
            ->andReturn($response);

        $coin = $coinDataSource->findById($coinId, $amountUsd)->getJsonData();

        $this->assertEquals('90', $coin["coinId"]);
        $this->assertEquals('Bitcoin', $coin['name']);
        $this->assertEquals('BTC', $coin['symbol']);
        $this->assertEquals(floatval($amountUsd) / floatval($coin["valueUsd"]), $coin['amount']);
        $this->assertEquals('123.45', $coin['valueUsd']);
    }

    /**
     * @test
     */
    public function doesNotGetUsdValueWhenCoinIdDoesNotExist()
    {
        $coinDataSource = new ApiCoinDataSource($this->coinloreApiService);
        $coinId = 'invalid_coin_id';

        $this->coinloreApiService
            ->shouldReceive('getCoinloreData')
            ->with($coinId)
            ->andReturn(null);

        $usdValue = $coinDataSource->getUsdValue($coinId);

        $this->assertNull($usdValue);
    }

    /**
     * @test
     */
    public function getUsdValueWhenCoinIdExists()
    {
        $coinDataSource = new ApiCoinDataSource($this->coinloreApiService);
        $coinId = '90';
        $response =  '[{"id": "90", "name": "Bitcoin", "symbol": "BTC", "price_usd": "123.45"}]';

        $this->coinloreApiService
            ->shouldReceive('getCoinloreData')
            ->with($coinId)
            ->andReturn($response);

        $usdValue = $coinDataSource->getUsdValue($coinId);

        $this->assertEquals(123.45, $usdValue);
    }
}
