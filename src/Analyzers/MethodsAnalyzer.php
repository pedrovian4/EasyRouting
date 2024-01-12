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


class MethodsAnalyzer implements NotationAnalyzerInterface {
    private ?NotationAnalyzerInterface $nextAnalyzer = null;


    /**
     * @param NotationAnalyzerInterface $analyzer
     * @return NotationAnalyzerInterface
     */
    public function setNext(NotationAnalyzerInterface $analyzer): NotationAnalyzerInterface {
        $this->nextAnalyzer = $analyzer;
        return $analyzer;
    }

    /**
     * @param ReflectionClass|string $controller
     * @return array
     * @throws \ReflectionException
     */
    public function analyze(ReflectionClass|string $controller): array
    {
        collect ($controller->getMethods ())
            ->map (fn(\ReflectionMethod $item) => Docs::notationArray ($item))
            ->filter (fn(Collection $item) => Str::contains ($item->get (0) , "@Easy"))
            ->values ()
            ->each(function (Collection $item){
                $method = Docs::getHttpMethods($item)??["GET"];
                $path = Docs::getProperty("path", $item, 0)??"";
                $name = Docs::getProperty("name", $item, 0);
                $middleware = Docs::getProperties("middleware", $item, 0);
                /** @var RouteNotationManager $instance */
                $instance = Factory::create(RouteNotationManager::class)
                    ->arguments([])
                    ->addMethods($method)
                    ->addPath($path)
                    ->addName($name)
                    ->addMiddleware($middleware);
                RouteInfoFacade::addRoute($instance);
            });
        if ($this->nextAnalyzer) {
            return $this->nextAnalyzer->analyze($controller);
        }

        return RouteInfoFacade::getInfo();
    }


}
