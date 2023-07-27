<?php

namespace Tests\app\Infrastructure\Controller;

use App\Application\DataSources\UserDataSource;
use App\Domain\User;
use Mockery;
use Tests\TestCase;

class PostOpenWalletControllerTest extends TestCase
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
    public function ifUserIdNotFoundThrowsError()
    {
        $this->userDataSource
        ->expects("findById")
        ->with("1")
        ->andReturn(null);

        $response = $this->post('api/wallet/open', ["user_id" => "1"]);

        $response->assertNotFound();
        $response->assertExactJson(['description' => 'A user with the specified ID was not found']);
    }

    /**
     * @test
     */
    public function ifNotUserIdThrowsBadRequest()
    {
        $this->userDataSource
        ->expects("findById")
        ->with(null)
        ->times(0)
        ->andReturn(null);

        $response = $this->post('api/wallet/open', ["user_id" => null]);

        $response->assertBadRequest();
        $response->assertExactJson(['error' => 'Bad Request','message' => 'The user id field is required.']);
    }

    /**
     * @test
     */
    public function ifBadUserIdThrowsBadRequest()
    {
        $this->userDataSource
            ->expects("findById")
            ->with(1)
            ->times(0)
            ->andReturn(new User(1));

        $response = $this->post('api/wallet/open', ["user_id" => 1]);

        $response->assertBadRequest();
        $response->assertExactJson(['error' => 'Bad Request','message' => 'The user id must be a string.']);
    }

    /**
     * @test
     */
    public function ifGoodUserIdCreatesWalletAndReturnsWalletId()
    {
        $this->userDataSource
            ->expects("findById")
            ->with("0")
            ->andReturn(new User("0"));

        $response = $this->post('api/wallet/open', ["user_id" => "0"]);

        $response->assertOk();
        $response->assertExactJson(['description' => 'successful operation','wallet_id' => 'wallet_0']);
    }
}
