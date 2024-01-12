<?php

namespace Petcha\EasyRouting\Analyzers;

use Illuminate\Support\Str;
use Illuminate\Support\Collection;
use Petcha\EasyRouting\Contracts\NotationAnalyzerInterface;
use Petcha\EasyRouting\Exceptions\ClassNotationNotFound;
use Petcha\EasyRouting\Exceptions\ClassNotationRouteTypeNotFound;
use Petcha\EasyRouting\Facades\RouteInfoFacade;
use Petcha\EasyRouting\Utils\Docs;
use ReflectionClass;

class ClassAnalyzer implements NotationAnalyzerInterface {
    private ?NotationAnalyzerInterface $nextAnalyzer = null;
    private mixed $data;

    public function __construct() {
        $this->nextAnalyzer = new MethodsAnalyzer();
    }

    public function setNext(NotationAnalyzerInterface $analyzer): NotationAnalyzerInterface {
        $this->nextAnalyzer = $analyzer;
        return $analyzer;
    }

    /**
     * @throws ClassNotationNotFound
     * @throws ClassNotationRouteTypeNotFound
     * @throws \ReflectionException
     */
    public function analyze($controller): NotationAnalyzerInterface|string|array
    {
        $controller = Str::replace([ ".php" , "/" ] , [ ".php" => "" , "/" => "\\" ] , $controller);
        $controllerClass = new ReflectionClass("\App\\$controller");

        $docs = Docs::notationArray($controllerClass);

        if ($docs->isEmpty()) {
            throw new ClassNotationNotFound();
        }
        if (!in_array($docs->first() , [ '@API' , '@Default' , '@Inertia' ])) {
            throw  new ClassNotationRouteTypeNotFound();
        }

        if (!preg_match("/@EasyRouting\\(.*\\)$/" , $docs->get(1) , $matches)) {
            throw  new ClassNotationRouteTypeNotFound("You must place <comment> @EasyRouting(...)</comment>  after the api type");
        }

        $controllerType = $this->getControllerType($docs);
        $controllerPath = Docs::getProperty('prefix' , $docs);
        $controllerMiddlewares = Docs::getProperties("middlewares" , $docs);
        $controllerBaseName = Docs::getProperty("name" , $docs);

        RouteInfoFacade::setControllerType($controllerType)
            ->setControllerDefaultPath($controllerPath)
            ->setControllerMiddleware($controllerMiddlewares)
            ->setControllerDefaultName($controllerBaseName);

        if ($this->nextAnalyzer) {
            return $this->nextAnalyzer->analyze($controllerClass);
        }
        return RouteInfoFacade::getInfo();
    }

    protected  function getControllerType(Collection $docs): string
    {
        return Str::lower(Str::replace('@', '', $docs->first()));
    }
}
