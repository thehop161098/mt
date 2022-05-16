<?php

namespace Core\Providers;

use Illuminate\Support\ServiceProvider;

class SummaryTradeServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(
            'Core\Repositories\Contracts\SummaryTradeInterface',
            'Core\Repositories\Eloquents\SummaryTradeRepository'
        );

       
    }
}
