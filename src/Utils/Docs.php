<?php

namespace Petcha\EasyRouting\Utils;

use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Nette\Utils\ReflectionMethod;
use ReflectionClass;
use ReflectionFunctionAbstract;

class Docs
{
    /**
     * @param ReflectionFunctionAbstract|ReflectionClass $controllerClass
     * @return string|array|null
     */
    private static function clear(ReflectionFunctionAbstract|ReflectionClass $controllerClass): string|array|null
    {
        $docs = $controllerClass->getDocComment();
        $docs = Str::replace([ "*" ], "", $docs);
        return preg_replace('/^\/|\/$/', '', $docs);
    }

    /**
     * @param ReflectionFunctionAbstract|ReflectionClass $controllerClass
     * @return Collection
     */
    public static function notationArray(ReflectionFunctionAbstract|ReflectionClass $controllerClass): Collection
    {
        return collect(explode("\n", self::clear($controllerClass)))->filter(function ($line) {
            return trim($line) !== '' && preg_match('/^\s*@\w+/', $line);
        })->map(function ($line) {
            return trim($line);
        })->values();
    }


    /**
     * @param string $name
     * @param Collection $docs
     * @param int $position
     * @return string|null
     */
    public static function getProperty(string $name, Collection $docs, int $position = 1): ?string
    {
        preg_match("/$name:'([^']+)'/", $docs->get($position), $matches);
        if (Arr::exists($matches, 1)) {
            return $matches[1];
        }
        return null;
    }

    /**
     * @param string $name
     * @param Collection $docs
     * @param int $position
     * @return array
     */
    public static function getProperties(string $name, Collection $docs, int $position = 1): array
    {

        preg_match("/$name:\[([^']+)\]/", $docs->get($position), $matches);
        if (Arr::exists($matches, 1)) {
            return explode(",", $matches[1]);
        }
        return [];
    }

    /**
     * @param Collection $docs
     * @return array
     */
    public static function getHttpMethods(Collection $docs): array
    {
        $pattern = "/methods:\[\s*'([^']+)'(?:,\s*'([^']+)')*\s*\]/";
        preg_match_all($pattern, $docs->get(0), $matches);
        if (isset($matches[1])) {
            $methods = array_filter(array_merge(...$matches));
            unset($methods[0]);
            return array_values(array_unique($methods));
        }
        return [];
    }

}

