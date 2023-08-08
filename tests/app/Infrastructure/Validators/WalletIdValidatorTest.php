<?php

namespace Tests\app\Infrastructure\Validators;

use App\Infrastructure\Validators\WalletIdValidator;
use Tests\TestCase;

class WalletIdValidatorTest extends TestCase
{
    private WalletIdValidator $walletIdValidator;
    protected function setUp(): void
    {
        parent::setUp();

        $this->walletIdValidator = new WalletIdValidator();
    }


    /**
     * @test
     */
    public function validWalletIdIfIdIsInteger()
    {
        $this->assertTrue($this->walletIdValidator->validateWalletId(123));
    }

    /**
     * @test
     */
    public function invalidWalletIdIfIdIsString()
    {
        $walletId = "one";

        $this->assertFalse($this->walletIdValidator->validateWalletId($walletId));
        $this->assertEquals(
            'The wallet id must be an integer, not a string.',
            $this->walletIdValidator->getMessage($walletId)
        );
    }

    /**
     * @test
     */
    public function invalidWalletIdIfIdIsDecimal()
    {
        $walletId = 0.1;

        $this->assertFalse($this->walletIdValidator->validateWalletId($walletId));
        $this->assertEquals(
            'The wallet id must be an integer, not a float.',
            $this->walletIdValidator->getMessage($walletId)
        );
    }

    /**
     * @test
     */
    public function invalidWalletIdIfIdIsNegative()
    {
        $walletId = -1;

        $this->assertFalse($this->walletIdValidator->validateWalletId($walletId));
        $this->assertEquals(
            'The wallet id must be a non-negative integer.',
            $this->walletIdValidator->getMessage($walletId)
        );
    }

    /**
     * @test
     */
    public function invalidWalletIdIfIdIsNull()
    {
        $walletId = null;

        $this->assertFalse($this->walletIdValidator->validateWalletId($walletId));
        $this->assertEquals(
            'Error occurred during wallet id validation.',
            $this->walletIdValidator->getMessage($walletId)
        );
    }
}
