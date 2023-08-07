<?php

namespace Tests\app\Application\Services;

use App\Application\Exceptions\WalletNotFoundException;
use App\Application\Services\WalletBalanceService;
use App\Domain\Coin;
use App\Domain\DataSources\CoinDataSource;
use App\Domain\DataSources\WalletDataSource;
use App\Domain\Wallet;
use Mockery;
use Symfony\Component\HttpFoundation\JsonResponse;
use Tests\TestCase;

class WalletBalanceServiceTest extends TestCase
{
    private WalletDataSource $walletDataSource;
    private CoinDataSource $coinDataSource;
    private WalletBalanceService $walletBalanceService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->walletDataSource = Mockery::mock(WalletDataSource::class);
        $this->coinDataSource = Mockery::mock(CoinDataSource::class);
        $this->walletBalanceService = new WalletBalanceService($this->walletDataSource, $this->coinDataSource);
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
        $this->expectExceptionCode(JsonResponse::HTTP_BAD_REQUEST);

        $this->walletBalanceService->getsBalance("notFound");
    }

    /**
     * @test
     */
    public function getsWalletBalanceIfWalletExists()
    {
        $coinId = '90';
        $coinValue = '50';
        $coinAmount = 4;
        $walletId = '0';
        $walletCoins = [
            (new Coin('90', 'Bitcoin', 'BTC', $coinAmount, $coinValue))->getJsonData(),
        ];
        $walletBuyTimeAccumulatedValue = '50';

        $this->walletDataSource
            ->shouldReceive("findById")
            ->with($walletId)
            ->once()
            ->andReturn(new Wallet('0'));
        $this->walletDataSource
            ->shouldReceive("getWalletById")
            ->with($walletId)
            ->once()
            ->andReturn(
                [
                    'BuyTimeAccumulatedValue' => $walletBuyTimeAccumulatedValue,
                    'coins' => $walletCoins
                ]
            );
        $this->coinDataSource
            ->shouldReceive("getUsdValue")
            ->with($coinId)
            ->andReturn($coinValue);

        $balance = $this->walletBalanceService->getsBalance($walletId);

        $this->assertEquals($balance, ($coinAmount * $coinValue) - $walletBuyTimeAccumulatedValue);
    }
}
