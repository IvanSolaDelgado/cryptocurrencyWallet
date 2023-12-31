<?php

namespace Tests\app\Application\Services;

use App\Application\Exceptions\CacheFullException;
use App\Application\Exceptions\UserNotFoundException;
use App\Application\Services\OpenWalletService;
use App\Domain\DataSources\UserDataSource;
use App\Domain\DataSources\WalletDataSource;
use App\Domain\User;
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
    public function userNotFoundIfUserDoesNotExist()
    {
        $this->userDataSource->shouldReceive('findById')->with('123')->andReturnNull();
        $this->walletDataSource->shouldReceive('saveWalletInCache')->never();

        $this->expectException(UserNotFoundException::class);
        $this->expectExceptionMessage('User not found');

        $this->openWalletService->execute('123');
    }

    /**
     * @test
     */
    public function createsWalletWhenUserExistsAndCacheIsNotFull()
    {
        $this->userDataSource->shouldReceive('findById')->with('1')->andReturn(new User('1'));
        $this->walletDataSource->shouldReceive('saveWalletInCache')->withNoArgs()->once()->andReturn('wallet_1');

        $this->openWalletService->execute('1');
    }

    /**
     * @test
     */
    public function throwsErrorIfCacheIsFull()
    {
        $this->userDataSource->shouldReceive('findById')->with('2')->andReturn(new User('2'));
        $this->walletDataSource->shouldReceive('saveWalletInCache')->withNoArgs()->once()->andReturn(null);

        $this->expectException(CacheFullException::class);
        $this->expectExceptionMessage('Cache is full');

        $this->openWalletService->execute('2');
    }
}
