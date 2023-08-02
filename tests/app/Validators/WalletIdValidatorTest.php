<?php

namespace Tests\app\Validators;

use App\Validators\WalletIdValidator;
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
        $this->assertFalse($this->walletIdValidator->validateWalletId("one"));
    }

    /**
     * @test
     */
    public function invalidWalletIdIfIdIsDecimal()
    {
        $this->assertFalse($this->walletIdValidator->validateWalletId(0.1));
    }

    /**
     * @test
     */
    public function invalidWalletIdIfIdIsNegative()
    {
        $this->assertFalse($this->walletIdValidator->validateWalletId(-1));
    }

    /**
     * @test
     */
    public function invalidWalletIdIfIdIsNull()
    {
        $this->assertFalse($this->walletIdValidator->validateWalletId(null));
    }
}
