<?php
namespace Petcha\EasyRouting\Contracts;
interface NotationAnalyzerInterface {
    public function analyze(string|\ReflectionClass $controller): NotationAnalyzerInterface|string|array;
    public function setNext(NotationAnalyzerInterface $analyzer): NotationAnalyzerInterface;

}
