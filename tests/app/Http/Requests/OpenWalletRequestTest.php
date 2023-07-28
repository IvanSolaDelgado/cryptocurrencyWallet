<?php

namespace Tests\app\Http\Requests;

use App\Http\Requests\OpenWalletRequest;
use Tests\TestCase;

class OpenWalletRequestTest extends TestCase
{
    /**
     * @test
     */
    public function itFailsValidationWhenUserIdIsNotProvided()
    {
        $data = [];
        $request = new OpenWalletRequest();

        $validator = $this->app['validator']->make($data, $request->rules());

        $this->assertTrue($validator->fails());
    }

    /**
     * @test
     */
    public function itFailsValidationWhenUserIdIsNotAString()
    {
        $data = [
            'user_id' => 12345,
        ];
        $request = new OpenWalletRequest();

        $validator = $this->app['validator']->make($data, $request->rules());

        $this->assertTrue($validator->fails());
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
