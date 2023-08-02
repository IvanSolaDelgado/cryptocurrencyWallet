<?php

namespace App\Validators;

use Illuminate\Support\Facades\Validator;

class WalletIdValidator
{
    public function validateWalletId($wallet_id): bool
    {
        $validator = Validator::make(['wallet_id' => $wallet_id], [
            'wallet_id' => 'required|int|min:0',
        ]);

        return !$validator->fails();
    }

    public function getMessage($wallet_id): string
    {
        if (is_string($wallet_id)) {
            return 'The wallet id must be an integer, not a string.';
        }

        if (is_float($wallet_id)) {
            return 'The wallet id must be an integer, not a float.';
        }

        if ((int)$wallet_id < 0) {
            return 'The wallet id must be a non-negative integer.';
        }

        return 'Error occurred during wallet id validation.';
    }
}
