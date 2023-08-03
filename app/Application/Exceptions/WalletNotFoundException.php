<?php

namespace App\Application\Exceptions;

use Symfony\Component\HttpFoundation\Response;
use Exception;

class WalletNotFoundException extends Exception
{
    public function __construct()
    {
        parent::__construct('Wallet not found', Response::HTTP_BAD_REQUEST);
    }
}
