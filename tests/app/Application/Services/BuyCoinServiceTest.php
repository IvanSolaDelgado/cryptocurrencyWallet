<?php

namespace Tests\app\Application\Services;

use App\Application\Exceptions\CoinNotFoundException;
use App\Application\Exceptions\WalletNotFoundException;
use App\Application\Services\BuyCoinService;
use App\Domain\Coin;
use App\Domain\DataSources\CoinDataSource;
use App\Domain\DataSources\WalletDataSource;
use App\Domain\Wallet;
use Mockery;
use Tests\TestCase;

class BuyCoinServiceTest extends TestCase
{
    private WalletDataSource $walletDataSource;
    private CoinDataSource $coinDataSource;
    private BuyCoinService $buyCoinService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->walletDataSource = Mockery::mock(WalletDataSource::class);
        $this->coinDataSource = Mockery::mock(CoinDataSource::class);
        $this->buyCoinService = new BuyCoinService($this->coinDataSource, $this->walletDataSource);
    }

    /**
     * @test
     */
    public function throwsErrorWhenCoinNotFound()
    {
        $coinId = '90';
        $amountUsd = 4;
        $walletId = '0';

        $this->coinDataSource
            ->shouldReceive('findById')
            ->with($coinId, $amountUsd)
            ->once()
            ->andReturn(null);

        $this->expectException(CoinNotFoundException::class);
        $this->expectExceptionMessage('Coin not found');

        $this->buyCoinService->execute($coinId, $walletId, $amountUsd);
    }

    /**
     * @test
     */
    public function throwsErrorWhenWalletNotFound()
    {
        $coinId = '90';
        $amountUsd = 4;
        $walletId = '0';

        $this->coinDataSource
            ->shouldReceive('findById')
            ->with($coinId, $amountUsd)
            ->once()
            ->andReturn(new Coin('90', 'Bitcoin', 'BTC', 4, 26829.64));
        $this->walletDataSource
            ->shouldReceive('findById')
            ->with($walletId)
            ->once()
            ->andReturn(null);

        $this->expectException(WalletNotFoundException::class);
        $this->expectExceptionMessage('Wallet not found');

        $this->buyCoinService->execute($coinId, $walletId, $amountUsd);
    }

    /**
     * @test
     */
    public function insertsCoinInWallet()
    {
        $coinId = '90';
        $amountUsd = 4;
        $walletId = '0';

        $this->coinDataSource
            ->shouldReceive('findById')
            ->with($coinId, $amountUsd)
            ->once()
            ->andReturn(new Coin('90', 'Bitcoin', 'BTC', 4, 26829.64));
        $this->walletDataSource
            ->shouldReceive('findById')
            ->with($walletId)
            ->once()
            ->andReturn(new Wallet('0'));
        $this->walletDataSource
            ->shouldReceive('insertCoinInWallet')
            ->with('0', Mockery::type(Coin::class))
            ->once();

        $this->buyCoinService->execute($coinId, '0', $amountUsd);
    }
}
