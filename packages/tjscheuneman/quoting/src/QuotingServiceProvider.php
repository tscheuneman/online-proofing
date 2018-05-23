<?php

namespace Tjscheuneman\Quoting;

use Illuminate\Support\ServiceProvider;

class QuotingServiceProvider extends ServiceProvider
{
    public function boot()
    {
        include __DIR__.'/routes.php';

        $this->publishes([
            __DIR__.'/migrations' => database_path('migrations')
        ], 'migrations');
    }

    public function register() {
        $this->app->make('Tjscheuneman\Quoting\QuotingController');


        $this->loadViewsFrom(__DIR__.'/views', 'quote');
    }
}