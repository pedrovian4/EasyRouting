<?php

namespace Petcha\EasyRouting\Analyzers;

use Petcha\EasyRouting\Contracts\NotationAnalyzerChain;
use Petcha\EasyRouting\Contracts\NotationAnalyzerInterface;


class MethodsAnalyzer implements NotationAnalyzerInterface {
    private ?NotationAnalyzerInterface $nextAnalyzer = null;
    private mixed $data;

    public function setNext(NotationAnalyzerInterface $analyzer): NotationAnalyzerInterface {
        $this->nextAnalyzer = $analyzer;
        return $analyzer;
    }

    public function analyze($controller): NotationAnalyzerInterface|string {

        if ($this->nextAnalyzer) {
            return $this->nextAnalyzer->analyze($controller);
        }

        return "Method analysis results";
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
