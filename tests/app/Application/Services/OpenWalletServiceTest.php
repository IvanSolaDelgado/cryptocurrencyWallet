<?php

namespace Tests\app\Application\Services;

use App\Domain\DataSources\UserDataSource;
use App\Domain\DataSources\WalletDataSource;
use App\Application\Services\OpenWalletService;
use App\Domain\User;
use App\Infrastructure\Exceptions\UserNotFoundException;
use Mockery;
use Tests\TestCase;

class OpenWalletServiceTest extends TestCase
{
    private UserDataSource $userDataSource;
    private WalletDataSource $walletDataSource;
    private OpenWalletService $openWalletService;

    /**
     * @setUp
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->userDataSource = Mockery::mock(UserDataSource::class);
        $this->walletDataSource = Mockery::mock(WalletDataSource::class);
        $this->openWalletService = new OpenWalletService($this->userDataSource, $this->walletDataSource);
    }

    /**
     * @test
     */
    public function returnsNullWhenUserNotFound()
    {
        $this->userDataSource->shouldReceive('findById')->with('123')->andReturnNull();
        $this->walletDataSource->shouldReceive('saveWalletInCache')->never();

        $this->expectException(UserNotFoundException::class);

        $this->openWalletService->createWallet('123');
    }

    /**
     * @test
     */
    public function createsWalletWhenUserExistsAndCacheIsNotFull()
    {
        $this->userDataSource->shouldReceive('findById')->with('1')->andReturn(new User('1'));
        $this->walletDataSource->shouldReceive('saveWalletInCache')->withNoArgs()->once()->andReturn('wallet_1');

        $this->openWalletService->createWallet('1');
    }

    /**
     * @test
     */
    public function returnsNullWhenUserExistsAndCacheIsFull()
    {
        $this->userDataSource->shouldReceive('findById')->with('2')->andReturn(new User('2'));
        $this->walletDataSource->shouldReceive('saveWalletInCache')->withNoArgs()->once()->andReturn(null);

        $this->openWalletService->createWallet('2');
    }
}
