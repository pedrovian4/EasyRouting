<?php

namespace Petcha\EasyRouting\Utils;

/**
 * @method addMethods(array|string|null $method)
 */
class Factory
{
    protected $className;
    protected $instance;

    public static function create($className): static
    {
        $factory = new static();
        $factory->className = $className;
        return $factory;
    }

    /**
     * @throws \ReflectionException
     */
    public function arguments(array $args): static
    {
        if (!class_exists($this->className)) {
            throw new \InvalidArgumentException("Class {$this->className} not found");
        }

        $reflection = new \ReflectionClass($this->className);
        $this->instance = $reflection->newInstanceArgs($args);
        return $this;
    }

    public function __call($name, $arguments)
    {
        if (!$this->instance) {
            throw new \RuntimeException("Instance of {$this->className} is not created. Call arguments() method first.");
        }

        if (!method_exists($this->instance, $name)) {
            throw new \BadMethodCallException("Method {$name} does not exist on {$this->className}");
        }

        return call_user_func_array([$this->instance, $name], $arguments);
    }
}
