<?php

namespace Petcha\EasyRouting\Utils;

use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use ReflectionClass;

class Docs
{
    private static function clear(ReflectionClass $controllerClass): string|array|null
    {
        $docs = $controllerClass->getDocComment();
        $docs = Str::replace([ "*" ], "", $docs);
        return preg_replace('/^\/|\/$/', '', $docs);
    }

    public static function notationArray( ReflectionClass $controllerClass ): Collection
    {
        return collect(explode("\n", self::clear($controllerClass)))->filter(function ( $line ) {
            return trim($line) !== '' && preg_match('/^\s*@\w+/', $line);
        })->map(function ( $line ) {
            return trim($line);
        })->values();
    }
}
