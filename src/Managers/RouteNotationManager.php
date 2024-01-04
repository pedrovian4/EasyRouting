<?php

namespace Petcha\EasyRouting\Managers;


use Petcha\EasyRouting\Exceptions\RouteRulesNotSeatedException;

class RouteNotationManager
{
    /**
     * @var null[]
     */
    private  array $route;
    public  function  __construct()
    {
        $this->route = [
            "path"=>null,
            "methods"=>null,
            "name"=>null,
            "middleware"=>null
        ];
    }

    /**
     * @param string $path
     * @return RouteNotationManager
     */
    public  function addPath(string $path): static
    {
        $this->route["path"] = $path;
        return $this;
    }

    /**
     * @param array $middleware
     * @return RouteNotationManager
     */
    public  function  addMiddleware(array $middleware): static
    {
        $this->route["middleware"] = $middleware;
        return $this;
    }

    /**
     * @param array $methods
     * @return RouteNotationManager
     */
    public  function addMethods(array $methods):static
    {
        $this->route["methods"] = $methods;
        return $this;
    }

    /**
     * @throws RouteRulesNotSeatedException
     */
    public function getRoute(): array
    {
        if(in_array(null,array_values($this->route))){
            throw new RouteRulesNotSeatedException();
        }
        return $this->route;
    }
}
