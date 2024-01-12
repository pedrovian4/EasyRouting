<?php

namespace Petcha\EasyRouting\Exceptions;

use Petcha\EasyRouting\Contracts\NotationExceptionInterface;
use Exception;
use Throwable;

class ClassNotationNotFound  extends Exception  implements NotationExceptionInterface
{

    public  function __construct(string $message = "Easy notation not found on controller", int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    public function getVerboseMessage($controller): string
    {
        return str_replace("controller", " <comment>: $controller</comment>", $this->getMessage());
    }
}
