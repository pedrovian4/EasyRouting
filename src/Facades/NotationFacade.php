<?php

namespace Petcha\EasyRouting\Facades;

use Illuminate\Support\Facades\Facade;
use Petcha\EasyRouting\Analyzers\NotationAnalyzer;

/**
 * @method static NotationAnalyzer|array analyze($controllerClass)
 * **/
class NotationFacade extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'notation.chain';
    }
}
