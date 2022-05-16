<?php

namespace Core\Providers;

use Illuminate\Support\ServiceProvider;

class TradingServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(
            'Core\Repositories\Contracts\CandleInterface',
            'Core\Repositories\Redis\RedisCandleRepository'
        );

        $this->app->singleton(
            'Core\Repositories\Contracts\NewCandleInterface',
            'Core\Repositories\Redis\RedisNewCandleRepository'
        );

        $this->app->singleton(
            'Core\Repositories\Contracts\CoinInterface',
            'Core\Repositories\Redis\RedisCoinRepository'
        );

        $this->app->singleton(
            'Core\Repositories\Contracts\OrderInterface',
            'Core\Repositories\Eloquents\OrderRepository'
        );

        $this->app->singleton(
            'Core\Repositories\Contracts\WalletGameInterface',
            'Core\Repositories\Redis\RedisWalletGameRepository'
        );

        $this->app->singleton(
            'Core\Repositories\Contracts\UserInterface',
            'Core\Repositories\Redis\RedisUserRepository'
        );
    }
}
