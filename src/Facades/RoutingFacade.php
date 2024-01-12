<?php

namespace Petcha\EasyRouting\Facades;

use Illuminate\Support\Facades\Facade;

class RoutingFacade extends  Facade
{
    protected static function getFacadeAccessor() : string
    {
        return 'easy.routing';
    }

}
