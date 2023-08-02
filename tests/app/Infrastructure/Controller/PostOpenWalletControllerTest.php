<?php

namespace Tests\app\Infrastructure\Controller;

use App\Domain\DataSources\UserDataSource;
use App\Domain\User;
use Mockery;
use Symfony\Component\HttpFoundation\JsonResponse;
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
    public function throwsErrorWhenUserIdNotFound()
    {
        $this->userDataSource
        ->expects("findById")
        ->with("1")
        ->andReturn(null);

        $response = $this->post('api/wallet/open', ["user_id" => "1"]);

        $response->assertStatus(JsonResponse::HTTP_NOT_FOUND);
        $response->assertExactJson(['description' => 'A user with the specified ID was not found']);
    }

    /**
     * @test
     */
    public function createsWalletWhenUserIsFound()
    {
        $this->userDataSource
            ->expects("findById")
            ->with("0")
            ->andReturn(new User("0"));

        $response = $this->post('api/wallet/open', ["user_id" => "0"]);

        $response->assertStatus(JsonResponse::HTTP_OK);
        $response->assertExactJson(['description' => 'successful operation','wallet_id' => 'wallet_0']);
    }
}
