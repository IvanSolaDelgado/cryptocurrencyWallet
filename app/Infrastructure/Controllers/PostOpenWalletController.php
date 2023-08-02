<?php

namespace App\Infrastructure\Controllers;

use App\Application\Services\OpenWalletService;
use App\Http\Requests\OpenWalletRequest;
use App\Application\DataSources\UserDataSource;
use App\Application\DataSources\WalletDataSource;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller as BaseController;

class PostOpenWalletController extends BaseController
{
    public function __invoke(OpenWalletRequest $openWalletRequest, OpenWalletService $openWalletService): JsonResponse
    {
        $userId = $openWalletRequest->input('user_id');
        $walletId = $openWalletService->createWallet($userId);

        if (is_null($walletId)) {
            return response()->json([
                'description' => 'A user with the specified ID was not found'
            ], Response::HTTP_NOT_FOUND);
        }

        if (str_contains($walletId, 'Cache is full')) {
            return response()->json([
                'description' => 'cache is full',
            ], Response::HTTP_NOT_FOUND);
        }

        return response()->json([
            'description' => 'successful operation',
            'wallet_id' => str($walletId)
        ], Response::HTTP_OK);
    }
}
