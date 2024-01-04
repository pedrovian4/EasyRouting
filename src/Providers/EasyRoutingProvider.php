<?php

namespace Petcha\EasyRouting\Providers;

use Illuminate\Support\ServiceProvider;
use Petcha\EasyRouting\Analyzers\NotationAnalyzer;
use Petcha\EasyRouting\Console\Commands\EasyRoutingCommand;
use Petcha\EasyRouting\Managers\RouteFileManager;
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
        $this->app->singleton('notation.chain', function ($app){
            return new NotationAnalyzer();
        });
        $this->app->singleton('route.info', function ($app){
            return new RouteFileManager();
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
