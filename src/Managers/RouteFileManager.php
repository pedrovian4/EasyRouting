<?php

namespace Petcha\EasyRouting\Managers;

use JetBrains\PhpStorm\ArrayShape;
use Petcha\EasyRouting\Exceptions\RouteRulesNotSeatedException;

class RouteFileManager
{
    /**
     * @var array
     */
    private array $controllerInfo;
    /**
     * @var array
     */
    private  array $routes;
    public function  __construct()
    {
        $this->controllerInfo = [
            'type' => null,
            'path' => null,
            'name' => null,
            'middleware' => [],
        ];
        $this->routes = [];
    }
    /**
     * @param string $type
     * @return $this
     */
    public function setControllerType(string $type): static
    {
        $this->controllerInfo['type'] = $type;
        return $this;
    }
    /**
     * @param string $path
     * @return $this
     */
    public  function  setControllerDefaultPath(string $path): static
    {
        $this->controllerInfo['path'] = $path;
        return $this;
    }
    /**
     * @param string $name
     * @return $this
     */
    public  function setControllerDefaultName (string $name): static
    {
        $this->controllerInfo['name'] = $name;
        return $this;
    }
    /**
     * @param array $middlewares
     * @return $this
     */
    public  function setControllerMiddleware (array $middlewares): static
    {
        $this->controllerInfo['middleware'] = $middlewares;
        return $this;
    }
    /**
     * @return array
     */
    public function getControllerInfo(): array
    {
        return $this->controllerInfo;
    }

    /**
     * @return null
     * @throws RouteRulesNotSeatedException
     */
    public function addRoute(RouteNotationManager $route):void
    {
        $this->routes[] = $route->getRoute();
    }
    public function getRoutes(): array
    {
        return $this->routes;
    }
    #[ArrayShape([ "controller" => "array", "route" => "array" ])]
    public  function getInfo(): array
    {
        return [
            "controller"=>$this->controllerInfo,
            "route"=> $this->routes
        ];
    }
}
