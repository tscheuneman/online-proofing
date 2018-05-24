<?php

namespace Tjscheuneman\Messaging;

use Illuminate\Support\ServiceProvider;

class MessagingServiceProvider extends ServiceProvider
{
    public function boot()
    {
        include __DIR__.'/routes.php';

        $this->publishes([
            __DIR__.'/migrations' => database_path('migrations')
        ], 'migrations');
    }

    public function register() {

    }
}