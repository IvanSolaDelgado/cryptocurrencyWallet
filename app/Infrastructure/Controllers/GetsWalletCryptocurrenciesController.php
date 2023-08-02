<?php

namespace App\Infrastructure\Controllers;

use App\Domain\DataSources\WalletDataSource;
use App\Validators\WalletIdValidator;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Cache;

class GetsWalletCryptocurrenciesController extends BaseController
{
    private WalletDataSource $walletDataSource;

    public function __construct(WalletDataSource $walletDataSource)
    {
        $this->walletDataSource = $walletDataSource;
    }
    public function __invoke($wallet_id, WalletIdValidator $walletIdValidator): JsonResponse
    {
        if (!$walletIdValidator->validateWalletId($wallet_id)) {
            return response()->json(['error' => 'Bad Request',
                'message' => $walletIdValidator->getMessage($wallet_id)], Response::HTTP_BAD_REQUEST);
        }
        if (is_null($this->walletDataSource->findById($wallet_id))) {
            return response()->json([
                'description' => 'A wallet with the specified ID was not found'
            ], Response::HTTP_NOT_FOUND);
        }

        $walletArray = Cache::get('wallet_' . $wallet_id);

        return response()->json([$walletArray['coins']], Response::HTTP_OK);
    }
}
