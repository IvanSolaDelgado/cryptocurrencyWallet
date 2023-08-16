<?php

namespace Tests\app\Infrastructure\Controllers\OpenWallet;

use App\Domain\DataSources\UserDataSource;
use App\Domain\User;
use Illuminate\Support\Facades\Cache;
use Mockery;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class OpenWalletControllerTest extends TestCase
{
    private UserDataSource $userDataSource;

    protected function setUp(): void
    {
        parent::setUp();
        $this->userDataSource = Mockery::mock(UserDataSource::class);
        $this->app->bind(UserDataSource::class, function () {
            return $this->userDataSource;
        });
    }

    /**
     * @test
     */
    public function throwsErrorWhenUserDoesNotExist()
    {
        $this->userDataSource
        ->expects("findById")
        ->with("1")
        ->andReturn(null);

        $response = $this->post('api/wallet/open', ["user_id" => "1"]);

        $response->assertStatus(Response::HTTP_NOT_FOUND);
        $response->assertExactJson(['description' => 'User not found']);
    }

    /**
     * @test
     */
    public function throwsErrorWhenCacheIsFull()
    {
        $this->userDataSource
            ->expects("findById")
            ->with("1")
            ->andReturn(new User("1"));
        Cache::shouldReceive('has')->andReturn(true);

        $response = $this->post('api/wallet/open', ["user_id" => "1"]);

        $response->assertStatus(Response::HTTP_INSUFFICIENT_STORAGE);
        $response->assertExactJson(['description' => 'Cache is full']);
    }

    /**
     * @test
     */
    public function createsWalletWhenUserExists()
    {
        $this->userDataSource
            ->expects("findById")
            ->with("0")
            ->andReturn(new User("0"));
        Cache::shouldReceive('has')->andReturn(false);
        Cache::shouldReceive('put')
            ->once()
            ->with('wallet_0', Mockery::type('array'));

        $response = $this->post('api/wallet/open', ["user_id" => "0"]);

        $response->assertStatus(Response::HTTP_OK);
        $response->assertExactJson(['description' => 'successful operation','wallet_id' => 'wallet_0']);
    }
}
