<?php

namespace App\Validators;

use Illuminate\Support\Facades\Validator;

class WalletIdValidator
{
    public static function validateWalletId($wallet_id): bool
    {
        $validator = Validator::make(['wallet_id' => $wallet_id], [
            'wallet_id' => 'required|int|min:0',
        ]);

        return !$validator->fails();
    }
}
