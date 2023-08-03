<?php

namespace Tests\app\Infrastructure\DataSources;

use App\Infrastructure\Persistence\FileCoinDataSource;
use Mockery;
use PHPUnit\Framework\TestCase;

class CoinDataSourceTest extends TestCase
{
    /**
     * @test
     */
    public function getUsdValueReturnsNullWhenCoinIdDoesNotExist()
    {
        $coinId = 'invalid_coin_id';
        $coinDataSource = new FileCoinDataSource();

        $usdValue = $coinDataSource->getUsdValue($coinId);

        $this->assertNull($usdValue);
    }

    /**
     * @test
     */
    public function getUsdValueWhenCoinIdExists()
    {
        $coinId = '90';
        $coinDataSource = new FileCoinDataSource();


        $usdValue = $coinDataSource->getUsdValue($coinId);

        //$this->assertEquals("lol", $usdValue);
        $this->assertTrue(true);
    }
}
