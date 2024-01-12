<?php

namespace Petcha\EasyRouting\Facades;

use Illuminate\Support\Facades\Facade;
use Petcha\EasyRouting\Analyzers\NotationAnalyzer;
use Petcha\EasyRouting\Managers\RouteFileManager;
use Petcha\EasyRouting\Managers\RouteNotationManager;

/**
 * @method static RouteFileManager setControllerType(string $controllerType)
 * @method static array getInfo()
 * @method static addRoute(RouteNotationManager $instance)
 */
class RouteInfoFacade extends Facade
{
    protected  static  function getFacadeAccessor(): string
    {
        return RouteFileManager::class;
    }
}
