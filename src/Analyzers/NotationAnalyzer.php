<?php

namespace Petcha\EasyRouting\Analyzers;

use Petcha\EasyRouting\Contracts\NotationAnalyzerInterface;
use Petcha\EasyRouting\Exceptions\ClassNotationNotFound;
use Petcha\EasyRouting\Exceptions\ClassNotationRouteTypeNotFound;

class NotationAnalyzer implements NotationAnalyzerInterface {
    private ?NotationAnalyzerInterface $nextAnalyzer = null;
    private mixed $data;

    public function setNext(NotationAnalyzerInterface $analyzer): NotationAnalyzerInterface {
        $this->nextAnalyzer = $analyzer;
        return $analyzer;
    }

    /**
     * @throws ClassNotationNotFound|ClassNotationRouteTypeNotFound
     */
    public function analyze($controller): NotationAnalyzerInterface|string {
        return (new ClassAnalyzer())->analyze($controller);
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
