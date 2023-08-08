<?php

namespace Tests\app\Infrastructure\Http\Requests;

use App\Infrastructure\Http\Requests\OpenWalletRequest;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\MessageBag;
use Tests\TestCase;

class OpenWalletRequestTest extends TestCase
{
    /**
     * @test
     */
    public function itFailsValidationWhenUserIdIsNotProvided()
    {
        $openWalletRequest = new OpenWalletRequest();
        $data = [];
        $expectedErrors = new MessageBag([
            'user_id' => ['The user id field is required.'],
        ]);

        $validator = Validator::make($data, $openWalletRequest->rules());

        $this->assertTrue($validator->fails());
        $this->assertEquals($expectedErrors, $validator->errors());
    }

    /**
     * @test
     */
    public function itFailsValidationWhenUserIdIsNotAString()
    {
        $openWalletRequest = new OpenWalletRequest();
        $data = [
            'user_id' => 12345,
        ];
        $expectedErrors = new MessageBag([
            'user_id' => ['The user id must be a string.'],
        ]);

        $validator = Validator::make($data, $openWalletRequest->rules());

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
        $request = new OpenWalletRequest();

        $validator = $this->app['validator']->make($data, $request->rules());

        $this->assertTrue($validator->passes());
    }
}
