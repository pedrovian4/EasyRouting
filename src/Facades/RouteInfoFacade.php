<?php

namespace Petcha\EasyRouting\Facades;

use Illuminate\Support\Facades\Facade;
use Petcha\EasyRouting\Managers\RouteFileManager;

class RouteInfoFacade extends Facade
{
    protected  static  function getFacadeAccessor(): string
    {
        return RouteFileManager::class;
    }
}
