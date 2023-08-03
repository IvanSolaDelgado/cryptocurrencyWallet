<?php

namespace Tests\app\Infrastructure\DataSources;

use App\Infrastructure\ApiServices\CoinloreApiService;
use App\Infrastructure\Persistence\FileCoinDataSource;
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
    public function getUsdValueReturnsNullWhenCoinIdDoesNotExist()
    {
        $coinId = 'invalid_coin_id';
        $coinDataSource = new FileCoinDataSource($this->coinloreApiService);

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
        $coinId = '90';
        $coinDataSource = new FileCoinDataSource($this->coinloreApiService);
        $responseJson = '[{"id": "90", "price_usd": "123.45"}, {"id": "91", "price_usd": "678.90"}]';

        $this->coinloreApiService
            ->shouldReceive('getCoinloreData')
            ->with($coinId)
            ->andReturn($responseJson);

        $usdValue = $coinDataSource->getUsdValue($coinId);

        $this->assertEquals(123.45, $usdValue);
    }
}
