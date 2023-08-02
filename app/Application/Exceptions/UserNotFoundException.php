<?php

namespace App\Application\Exceptions;

use Exception;
use Symfony\Component\HttpFoundation\Response;

class UserNotFoundException extends Exception
{
    public function __construct()
    {
        parent::__construct('User not found', Response::HTTP_OK);
    }
}
