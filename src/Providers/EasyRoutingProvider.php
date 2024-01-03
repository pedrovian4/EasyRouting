<?php

namespace Petcha\EasyRouting\Providers;

use Illuminate\Support\ServiceProvider;
use Petcha\EasyRouting\Console\Commands\EasyRoutingCommand;
use Petcha\EasyRouting\Managers\RouteManager;
use Petcha\EasyRouting\Services\EasyRoutingService;

class EasyRoutingProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton('easy.routing', function ($app) {
            return new EasyRoutingService();
        });
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
