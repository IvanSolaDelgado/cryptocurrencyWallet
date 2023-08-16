<?php

namespace Tests\app\Application\DataSources;

use App\Domain\Coin;
use App\Domain\DataSources\CoinDataSource;
use App\Domain\DataSources\WalletDataSource;
use App\Domain\Wallet;
use App\Infrastructure\Persistence\FileWalletDataSource;
use Illuminate\Support\Facades\Cache;
use Mockery;
use Tests\TestCase;

class WalletDataSourceTest extends TestCase
{
    private CoinDataSource $coinDataSource;
    private WalletDataSource $walletDataSource;

    protected function setUp(): void
    {
        parent::setUp();

        $this->walletDataSource = new FileWalletDataSource();
        $this->coinDataSource = Mockery::mock(CoinDataSource::class);
        $this->app->bind(CoinDataSource::class, function () {
            return $this->coinDataSource;
        });
    }

    /**
     * @test
     */
    public function doesNotFindAWalletIfWalletDoesNotExist()
    {
        Cache::shouldReceive('has')->andReturn(false);

        $this->assertEquals(null, $this->walletDataSource->findById('wallet_0'));
    }

    /**
     * @test
     */
    public function findAWalletIfWalletExists()
    {
        Cache::shouldReceive('has')->andReturn(true);

        $this->assertEquals(new Wallet('wallet_0'), $this->walletDataSource->findById('wallet_0'));
    }

    /**
     * @test
     */
    public function savesWalletWhenCacheIsNotFull()
    {
        Cache::shouldReceive('has')->andReturn(false);
        Cache::shouldReceive('put')
            ->once()
            ->with('wallet_0', Mockery::type('array'));

        $this->assertEquals('wallet_0', $this->walletDataSource->saveWalletInCache());
    }

    /**
     * @test
     */
    public function returnsNullWhenCacheIsFull()
    {
        Cache::shouldReceive('has')->andReturn(true);

        $this->assertEquals(null, $this->walletDataSource->saveWalletInCache());
    }

    /**
     * @test
     */
    public function doesNotSellCoinFromWalletWhenWalletDoesNotExist()
    {
        $coin = new Coin('90', 'Bitcoin', 'BTC', 32, 21312);

        Cache::shouldReceive('has')->once()->andReturn(false);
        Cache::shouldReceive('get')->never();
        Cache::shouldReceive('put')->never();

        $this->walletDataSource->sellCoinFromWallet('0', $coin, 4);
    }

    /**
     * @test
     */
    public function doesNotInsertCoinInWalletWhenWalletDoesNotExist()
    {
        $coin = new Coin('90', 'Bitcoin', 'BTC', 32, 21312);

        Cache::shouldReceive('has')->once()->andReturn(false);
        Cache::shouldReceive('get')->never();
        Cache::shouldReceive('put')->never();

        $this->walletDataSource->insertCoinInWallet('0', $coin);
    }

    /**
     * @test
     */
    public function insertsCoinInWalletWhenWalletExists()
    {
        $coin = new Coin('90', 'Bitcoin', 'BTC', 4, 26829.64);

        Cache::shouldReceive('has')->andReturn(true);
        Cache::shouldReceive('get')->once()->with('wallet_0')->andReturn(
            [
                'walletId' => '0',
                'BuyTimeAccumulatedValue' => 0,
                'coins' => [],
            ]
        );
        Cache::shouldReceive('put')
            ->with('wallet_0', Mockery::type('array'));

        $this->walletDataSource->insertCoinInWallet('0', $coin);
    }

    /**
     * @test
     */
    public function sellsCoinFromWalletWhenWalletExists()
    {
        $coin = new Coin('90', 'Bitcoin', 'BTC', 4, 26829.64);

        Cache::shouldReceive('has')->andReturn(true);
        Cache::shouldReceive('get')->once()->with('wallet_0')->andReturn(
            [
                "walletId" => "0",
                "BuyTimeAccumulatedValue" => 0,
                "coins" => [[
                    "coinId" => "90",
                    "name" => "Bitcoin",
                    "symbol" => "BTC",
                    "amount" => 4,
                    "valueUsd" => 26829.64,
                ]]
            ]
        );
        Cache::shouldReceive('put')
            ->with('wallet_0', Mockery::type('array'));

        $this->walletDataSource->sellCoinFromWallet('0', $coin, 2);
    }
}
