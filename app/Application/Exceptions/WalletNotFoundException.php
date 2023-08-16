<?php

namespace App\Application\Exceptions;

use Exception;
use Symfony\Component\HttpFoundation\Response;

class WalletNotFoundException extends Exception
{
    public function __construct()
    {
        parent::__construct('Wallet not found', Response::HTTP_NOT_FOUND);
    }
}
