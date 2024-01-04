<?php

namespace Petcha\EasyRouting\Analyzers;

use Illuminate\Support\Str;
use Nwidart\Modules\Collection;
use Petcha\EasyRouting\Contracts\NotationAnalyzerInterface;
use Petcha\EasyRouting\Exceptions\ClassNotationNotFound;
use Petcha\EasyRouting\Exceptions\ClassNotationRouteTypeNotFound;
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
     */
    public function analyze($controller): NotationAnalyzerInterface|string
    {
        $controller = Str::replace([".php", "/"], [".php"=>"","/"=>"\\"], $controller);
        $controllerClass = new ReflectionClass("\App\\$controller");

        /**@var Collection $docs**/
        $docs = Docs::notationArray($controllerClass);

        if($docs->isEmpty()){
            throw new ClassNotationNotFound();
        }
        if(!in_array($docs->first(),['@API', '@Default', '@Inertia'])){
            throw  new ClassNotationRouteTypeNotFound();
        }


        if ($this->nextAnalyzer) {
            return $this->nextAnalyzer->analyze($controllerClass);
        }
        return "Class analysis results";
    }

    public function setSharedNotation(mixed $data): void
    {
        $this->data = $data;
    }

    public function getSharedNotation(): mixed
    {
        return  $this->data;
    }
}
