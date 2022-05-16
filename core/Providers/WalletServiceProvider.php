<?php

namespace Core\Providers;

use Illuminate\Support\ServiceProvider;

class WalletServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(
            'Core\Repositories\Contracts\WalletInterface',
            'Core\Repositories\Eloquents\WalletRepository'
        );

        $this->app->singleton(
            'Core\Repositories\Contracts\WalletGameInterface',
            'Core\Repositories\Eloquents\WalletGameRepository'
        );
    }
}
