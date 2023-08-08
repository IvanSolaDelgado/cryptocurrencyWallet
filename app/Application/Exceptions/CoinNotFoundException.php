<?php

namespace App\Application\Exceptions;

use Exception;
use Symfony\Component\HttpFoundation\Response;

class CoinNotFoundException extends Exception
{
    public function __construct()
    {
        parent::__construct('Coin not found', Response::HTTP_BAD_REQUEST);
    }
}
