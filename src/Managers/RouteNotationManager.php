<?php

namespace Petcha\EasyRouting\Managers;


use Petcha\EasyRouting\Exceptions\RouteRulesNotSeatedException;

class RouteNotationManager
{
    /**
     * @var null|string[]
     */
    private  array $route;
    public  function  __construct()
    {
        $this->route = [
            "path"=>null,
            "methods"=>[],
            "name"=>null,
            "middleware"=>[],
            "function"=>null
        ];
    }

    /**
     * @param string $path
     * @return RouteNotationManager
     */
    public function addPath(string $path): static
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
     * @param string $name
     * @return $this
     */
    public  function  addName(string $name): static
    {
        $this->route['name'] = $name;
        return $this;
    }
    /**
     * @param string $function
     * @return $this
     */
    public  function addFunction(string $function): static
    {
        $this->route['function'] = $function;
        return $this;
    }
    /**
     * @return ?string
     */
    public  function getPath(): ?string
    {
        return $this->route['path'];
    }

    /**
     * @return string|array
     */
    public  function  getMiddleware():string|array
    {
        if(count($this->route['middleware']) == 1){
            return $this->route['middleware'][0];
        }
        return $this->route['middleware'];
    }

    public function  getMethods():array|string
    {
        if(count($this->route['methods']) == 1){
            return $this->route['methods'][0];
        }
        return $this->route['methods'];
    }
    public  function  getName():?string
    {
        return $this->route['name'];
    }

    /**
     * @throws RouteRulesNotSeatedException
     * @return string[]
     */
    public function getRoute(): array
    {
        if(is_null($this->route["name"])){
            throw new RouteRulesNotSeatedException();
        }
        return $this->route;
    }
}
