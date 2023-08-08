<?php

namespace Tests\app\Application\Services;

use App\Application\Exceptions\WalletNotFoundException;
use App\Application\Services\WalletCryptocurrenciesService;
use App\Domain\Coin;
use App\Domain\DataSources\WalletDataSource;
use App\Domain\Wallet;
use Illuminate\Support\Facades\Cache;
use Mockery;
use Tests\TestCase;

class WalletCryptocurrenciesServiceTest extends TestCase
{
    private WalletCryptocurrenciesService $walletCryptocurrenciesService;
    private WalletDataSource $walletDataSource;

    protected function setUp(): void
    {
        parent::setUp();

        $this->walletDataSource = Mockery::mock(WalletDataSource::class);
        $this->walletCryptocurrenciesService = new WalletCryptocurrenciesService($this->walletDataSource);
    }

    /**
     * @test
     */
    public function walletNotFoundIfWalletDoesNotExist()
    {
        $this->walletDataSource
            ->shouldReceive("findById")
            ->with("notFound")
            ->once()
            ->andReturn(null);

        $this->expectException(WalletNotFoundException::class);
        $this->expectExceptionMessage('Wallet not found');

        $this->walletCryptocurrenciesService->execute("notFound");
    }

    /**
     * @test
     */
    public function getsWalletCryptocurrenciesWhenWalletFound()
    {
        $walletId = "0";
        $coins = [
            (new Coin('90', 'Bitcoin', 'BTC', 4, 26829.64))->getJsonData(),
            (new Coin('80', 'Ethereum', 'ETH', 10, 1830))->getJsonData(),
            (new Coin('518', 'Tether', 'USDT', 2, 1.00))->getJsonData(),
        ];

        $this->walletDataSource
            ->shouldReceive("findById")
            ->with("0")
            ->andReturn(new Wallet($walletId));
        $this->walletDataSource
            ->shouldReceive('getWalletById')
            ->with($walletId)
            ->andReturn(['coins' => $coins]);
        Cache::shouldReceive("get")
            ->with("wallet_0")
            ->andReturn(new Wallet('0'));

        $this->assertEquals($coins, $this->walletCryptocurrenciesService->execute($walletId));
    }
}
