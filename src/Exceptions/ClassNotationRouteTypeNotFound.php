<?php

namespace Petcha\EasyRouting\Exceptions;

use Illuminate\Support\Str;
use Petcha\EasyRouting\Contracts\NotationExceptionInterface;
use Throwable;

class ClassNotationRouteTypeNotFound extends \Exception implements NotationExceptionInterface
{
    public function __construct(string $message = "Your controller must at least one Router Type: @API, @Default, @Inertia", int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    public function getVerboseMessage($controller): string
    {
        return Str::replace("@API, @Default, @Inertia", "<comment>:  @API, @Default, @Inertia </comment>",$this->getMessage());
    }
}
