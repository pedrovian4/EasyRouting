<?php

namespace Petcha\EasyRouting\Analyzers;

use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Petcha\EasyRouting\Contracts\NotationAnalyzerInterface;
use Petcha\EasyRouting\Facades\RouteInfoFacade;
use Petcha\EasyRouting\Managers\RouteNotationManager;
use Petcha\EasyRouting\Utils\Docs;
use Petcha\EasyRouting\Utils\Factory;
use ReflectionClass;
use ReflectionMethod;

class MethodsAnalyzer implements NotationAnalyzerInterface {
    private ?NotationAnalyzerInterface $nextAnalyzer = null;

    public function setNext(NotationAnalyzerInterface $analyzer): NotationAnalyzerInterface {
        $this->nextAnalyzer = $analyzer;
        return $analyzer;
    }

    public function analyze(ReflectionClass|string $controller): array
    {
        collect($controller->getMethods())
            ->map(function (ReflectionMethod $method) {
                $notationArray = Docs::notationArray($method);
                $notationArray['function'] = $method->getName();  // Corrected the key name here
                return $notationArray;
            })
            ->filter(fn(Collection $item) => Str::contains($item->get(0), "@Easy"))
            ->each(/**
             * @throws \ReflectionException
             */ function (Collection $item) {
                $method = Docs::getHttpMethods($item) ?? ["GET"];
                $path = Docs::getProperty("path", $item, 0) ?? "";
                $name = Docs::getProperty("name", $item, 0);
                $middleware = Docs::getProperties("middleware", $item, 0);
                $function = $item['function'];

                /** @var RouteNotationManager $instance */
                $instance = Factory::create(RouteNotationManager::class)
                    ->arguments([])
                    ->addMethods($method)
                    ->addPath($path)
                    ->addName($name)
                    ->addMiddleware($middleware)
                    ->addFunction($function);

                RouteInfoFacade::addRoute($instance);
            });

        if ($this->nextAnalyzer) {
            return $this->nextAnalyzer->analyze($controller);
        }

        return RouteInfoFacade::getInfo();
    }
}
