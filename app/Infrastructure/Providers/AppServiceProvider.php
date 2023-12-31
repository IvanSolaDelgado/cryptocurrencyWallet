<?php

namespace App\Infrastructure\Providers;

use App\Domain\DataSources\CoinDataSource;
use App\Domain\DataSources\UserDataSource;
use App\Domain\DataSources\WalletDataSource;
use App\Infrastructure\ApiServices\CoinloreApiService;
use App\Infrastructure\Persistence\ApiCoinDataSource;
use App\Infrastructure\Persistence\FileUserDataSource;
use App\Infrastructure\Persistence\CacheWalletDataSource;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(): void
    {
        $this->app->bind(UserDataSource::class, function () {
            return new FileUserDataSource();
        });
        $this->app->bind(CoinDataSource::class, function () {
            return new ApiCoinDataSource(new CoinloreApiService());
        });
        $this->app->bind(WalletDataSource::class, function () {
            return new CacheWalletDataSource();
        });
    }
}
