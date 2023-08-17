<?php

namespace App\Domain;

class User
{
    private string $userId;

    public function __construct(string $userId)
    {
        $this->userId = $userId;
    }
}
