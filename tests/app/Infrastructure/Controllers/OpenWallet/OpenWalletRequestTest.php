<?php

namespace Tests\app\Infrastructure\Controllers\OpenWallet;

use App\Infrastructure\Controllers\OpenWallet\OpenWalletRequest;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\MessageBag;
use Tests\TestCase;

class OpenWalletRequestTest extends TestCase
{
    private OpenWalletRequest $openWalletRequest;

    protected function setUp(): void
    {
        parent::setUp();

        $this->openWalletRequest = new OpenWalletRequest();
    }


    /**
     * @test
     */
    public function itFailsValidationWhenUserIdIsNotProvided()
    {
        $data = [];
        $expectedErrors = new MessageBag([
            'user_id' => ['The user id field is required.'],
        ]);

        $validator = Validator::make($data, $this->openWalletRequest->rules());

        $this->assertTrue($validator->fails());
        $this->assertEquals($expectedErrors, $validator->errors());
    }

    /**
     * @test
     */
    public function itFailsValidationWhenUserIdIsNotAString()
    {
        $data = [
            'user_id' => 12345,
        ];
        $expectedErrors = new MessageBag([
            'user_id' => ['The user id must be a string.'],
        ]);

        $validator = Validator::make($data, $this->openWalletRequest->rules());

        $this->assertTrue($validator->fails());
        $this->assertEquals($expectedErrors, $validator->errors());
    }

    /**
     * @test
     */
    public function itPassesValidationWithValidData()
    {
        $data = [
            'user_id' => '1',
        ];

        $validator = $this->app['validator']->make($data, $this->openWalletRequest->rules());

        $this->assertTrue($validator->passes());
    }
}
