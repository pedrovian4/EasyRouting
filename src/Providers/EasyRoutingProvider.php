<?php

namespace Petcha\EasyRouting\Providers;

use Illuminate\Support\ServiceProvider;
use Petcha\EasyRouting\Console\Commands\EasyRoutingCommand;

class EasyRoutingProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        if($this->app->runningInConsole()){
            $this->commands([
                EasyRoutingCommand::class
            ]);
        }
    }
}
