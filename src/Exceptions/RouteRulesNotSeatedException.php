<?php

namespace Petcha\EasyRouting\Exceptions;

use Exception;
use Petcha\EasyRouting\Contracts\RouteInformationExceptionInterface;
use Throwable;

class RouteRulesNotSeatedException extends Exception implements  RouteInformationExceptionInterface
{
    public function __construct(string $message = "Route information  missing", int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    public function getVerboseMessage(): string
    {
        return  $this->getMessage();
    }
}
