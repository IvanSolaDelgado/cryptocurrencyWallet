<?php

namespace Tests\app\Application\Services;

use App\Application\DataSources\UserDataSource;
use App\Application\DataSources\WalletDataSource;
use App\Application\Services\OpenWalletService;
use App\Domain\User;
use Mockery;
use Tests\TestCase;

class OpenWalletServiceTest extends TestCase
{
    private UserDataSource $userDataSource;
    private WalletDataSource $walletDataSource;
    private OpenWalletService $openWalletService;

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
        $this->userDataSource->shouldReceive('findById')->with('123')->andReturn(null);

        $result = $this->openWalletService->openWallet('123');

        $this->assertNull($result);
    }

    /**
     * @test
     */
    public function createsWalletWhenUserExists()
    {
        $this->userDataSource->shouldReceive('findById')->with('1')->andReturn(new User('1'));
        $this->walletDataSource->shouldReceive('saveWalletInCache')->andReturn('wallet_1');

        $result = $this->openWalletService->openWallet('1');

        $this->assertEquals('wallet_1', $result);
    }

    /**
     * @test
     */
    public function returnsNullWhenCacheIsFull()
    {
        $this->userDataSource->shouldReceive('findById')->with('2')->andReturn(new User('2'));
        $this->walletDataSource->shouldReceive('saveWalletInCache')->andReturn(null);

        $result = $this->openWalletService->openWallet('2');

        $this->assertNull($result);
    }
}
