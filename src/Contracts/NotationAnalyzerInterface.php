<?php
namespace Petcha\EasyRouting\Contracts;
interface NotationAnalyzerInterface {
    public function analyze(string|\ReflectionClass $controller): NotationAnalyzerInterface|string;
    public function setNext(NotationAnalyzerInterface $analyzer): NotationAnalyzerInterface;
    public  function setSharedNotation(mixed $data);
    public function  getSharedNotation(): mixed;
}
