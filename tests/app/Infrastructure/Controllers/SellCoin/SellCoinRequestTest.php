<?php

namespace Tests\app\Infrastructure\Controllers\SellCoin;

use App\Infrastructure\Controllers\SellCoin\SellCoinRequest;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\MessageBag;
use Tests\TestCase;

class SellCoinRequestTest extends TestCase
{
    private SellCoinRequest $coinRequest;

    protected function setUp(): void
    {
        parent::setUp();

        $this->coinRequest = new SellCoinRequest();
    }

    /**
     * @test
     */
    public function itFailsValidationWhenCoinIdIsNotProvided()
    {
        $data = [
            "wallet_id" => "0",
            "amount_usd" => 1,
        ];
        $expectedErrors = new MessageBag([
            'coin_id' => ['The coin id field is required.'],
        ]);

        $validator = Validator::make($data, $this->coinRequest->rules());

        $this->assertTrue($validator->fails());
        $this->assertEquals($expectedErrors, $validator->errors());
    }

    /**
     * @test
     */
    public function itFailsValidationWhenCoinIdIsNotString()
    {
        $data = [
            "coin_id" => 0,
            "wallet_id" => "0",
            "amount_usd" => 1,
        ];
        $expectedErrors = new MessageBag([
            'coin_id' => ['The coin id must be a string.'],
        ]);

        $validator = Validator::make($data, $this->coinRequest->rules());

        $this->assertTrue($validator->fails());
        $this->assertEquals($expectedErrors, $validator->errors());
    }

    /**
     * @test
     */
    public function itFailsValidationWhenWalletIdIsNotProvided()
    {
        $data = [
            "coin_id" => "0",
            "amount_usd" => 1,
        ];
        $expectedErrors = new MessageBag([
            'wallet_id' => ['The wallet id field is required.'],
        ]);

        $validator = Validator::make($data, $this->coinRequest->rules());

        $this->assertTrue($validator->fails());
        $this->assertEquals($expectedErrors, $validator->errors());
    }

    /**
     * @test
     */
    public function itFailsValidationWhenWalletIdIsNotString()
    {
        $data = [
            "coin_id" => "0",
            "wallet_id" => 1,
            "amount_usd" => 1,
        ];
        $expectedErrors = new MessageBag([
            'wallet_id' => ['The wallet id must be a string.'],
        ]);

        $validator = Validator::make($data, $this->coinRequest->rules());

        $this->assertTrue($validator->fails());
        $this->assertEquals($expectedErrors, $validator->errors());
    }

    /**
     * @test
     */
    public function itFailsValidationWhenAmountIsNotProvided()
    {
        $data = [
            "coin_id" => "0",
            "wallet_id" => "0",
        ];
        $expectedErrors = new MessageBag([
            'amount_usd' => ['The amount usd field is required.'],
        ]);

        $validator = Validator::make($data, $this->coinRequest->rules());

        $this->assertTrue($validator->fails());
        $this->assertEquals($expectedErrors, $validator->errors());
    }

    /**
     * @test
     */
    public function itFailsValidationWhenAmountIsNotAPositiveInteger()
    {
        $data = [
            "coin_id" => "0",
            "wallet_id" => "0",
            "amount_usd" => -1,
        ];
        $expectedErrors = new MessageBag([
            'amount_usd' => ['The amount usd must be at least 0.'],
        ]);

        $validator = Validator::make($data, $this->coinRequest->rules());

        $this->assertTrue($validator->fails());
        $this->assertEquals($expectedErrors, $validator->errors());
    }

    /**
     * @test
     */
    public function itPassesValidationWithValidData()
    {
        $data = [
            "coin_id" => "0",
            "wallet_id" => "0",
            "amount_usd" => 0,
        ];

        $validator = Validator::make($data, $this->coinRequest->rules());

        $this->assertTrue($validator->passes());
    }
}
