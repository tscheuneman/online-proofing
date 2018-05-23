<?php

namespace Tjscheuneman\Proofing;

use Illuminate\Support\ServiceProvider;

class ProofingServiceProvider extends ServiceProvider
{
    public function boot()
    {
        include __DIR__.'/routes.php';

        $this->publishes([
            __DIR__.'/migrations' => database_path('migrations')
        ], 'migrations');

        $this->publishes([
            __DIR__.'/public' => public_path(''),
        ], 'public');
    }

    public function register() {
        $this->loadViewsFrom(__DIR__.'/views', 'proof');
    }
}