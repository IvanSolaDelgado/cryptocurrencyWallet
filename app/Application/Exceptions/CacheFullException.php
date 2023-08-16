<?php

namespace App\Application\Exceptions;

use Exception;
use Symfony\Component\HttpFoundation\Response;

class CacheFullException extends Exception
{
    public function __construct()
    {
        parent::__construct('Cache is full', Response::HTTP_INSUFFICIENT_STORAGE);
    }
}
