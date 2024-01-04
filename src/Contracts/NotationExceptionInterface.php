<?php

namespace Petcha\EasyRouting\Contracts;

interface NotationExceptionInterface
{
    public  function getVerboseMessage($controller):string;
}
