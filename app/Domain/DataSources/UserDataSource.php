<?php

namespace App\Domain\DataSources;

use App\Domain\User;

interface UserDataSource
{
    public function findById(string $userId): ?User;
}
