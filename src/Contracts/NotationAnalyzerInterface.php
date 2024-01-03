<?php

namespace Petcha\EasyRouting\Contracts;

interface NotationAnalyzerInterface {
    public function analyze($notation);
    public function setNext(NotationAnalyzerInterface $analyzer): NotationAnalyzerInterface;
}
