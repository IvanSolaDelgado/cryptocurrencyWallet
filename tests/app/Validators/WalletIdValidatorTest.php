<?php

namespace Tests\app\Validators;

use App\Validators\WalletIdValidator;
use Tests\TestCase;

class WalletIdValidatorTest extends TestCase
{
    /**
     * @test
     */
    public function validWalletIdIfIdIsInteger()
    {
        $this->assertTrue(WalletIdValidator::validateWalletId(123));
    }

    /**
     * @test
     */
    public function invalidWalletIdIfIdIsString()
    {
        $this->assertFalse(WalletIdValidator::validateWalletId("one"));
    }

    /**
     * @test
     */
    public function invalidWalletIdIfIdIsDecimal()
    {
        $this->assertFalse(WalletIdValidator::validateWalletId(0.1));
    }

    /**
     * @test
     */
    public function invalidWalletIdIfIdIsNegative()
    {
        $this->assertFalse(WalletIdValidator::validateWalletId(-1));
    }

    /**
     * @test
     */
    public function invalidWalletIdIfIdIsNull()
    {
        $this->assertFalse(WalletIdValidator::validateWalletId(null));
    }
}
