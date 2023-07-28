<?php

namespace App\Infrastructure\Controllers;

use App\Application\DataSources\UserDataSource;
use App\Application\DataSources\WalletDataSource;
use App\Application\Services\OpenWalletService;
use App\Http\Requests\OpenWalletRequest;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller as BaseController;

class PostOpenWalletController extends BaseController
{
    private OpenWalletService $openWalletService;

    public function __construct(OpenWalletService $openWalletService)
    {
        $this->openWalletService = $openWalletService;
    }
    public function __invoke(OpenWalletRequest $request): JsonResponse
    {
        $userId = $request->input('user_id');
        $walletId = $this->openWalletService->openWallet($userId);

        if (is_null($walletId)) {
            return response()->json([
                'description' => 'A user with the specified ID was not found'
            ], Response::HTTP_NOT_FOUND);
        }

        if ($walletId) {
            return response()->json([
                'description' => 'successful operation',
                'wallet_id' => str($walletId)
            ], Response::HTTP_OK);
        }

        return response()->json([
            'description' => 'cache is full',
        ], Response::HTTP_NOT_FOUND);
    }
}
