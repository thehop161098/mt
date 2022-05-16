<?php

namespace Core\Providers;

use Illuminate\Support\ServiceProvider;

class AgencyServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(
            'Core\Repositories\Contracts\HistoryWalletInterface',
            'Core\Repositories\Eloquents\HistoryWalletRepository'
        );
    }
}
