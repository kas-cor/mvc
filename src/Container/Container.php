<?php

declare(strict_types=1);

namespace src\Container;

use Psr\Container\ContainerInterface;
use ReflectionClass;
use ReflectionException;
use ReflectionParameter;
use Exception;

/**
 * Dependency Injection Container
 */
class Container implements ContainerInterface
{
    private array $instances = [];
    private array $shared = [];
    private array $factories = [];
    private array $aliases = [];

    /**
     * Set a shared instance
     */
    public function set(string $id, mixed $instance): void
    {
        $this->instances[$id] = $instance;
    }

    /**
     * Register a shared service
     */
    public function share(string $id, callable $concrete): void
    {
        $this->shared[$id] = $concrete;
    }

    /**
     * Register a factory (creates new instance each time)
     */
    public function factory(string $id, callable $factory): void
    {
        $this->factories[$id] = $factory;
    }

    /**
     * Register an alias
     */
    public function alias(string $alias, string $id): void
    {
        $this->aliases[$alias] = $id;
    }

    /**
     * Get a service from the container
     * @throws Exception
     */
    public function get(string $id): mixed
    {
        // Resolve alias
        if (isset($this->aliases[$id])) {
            $id = $this->aliases[$id];
        }

        // Return existing instance
        if (isset($this->instances[$id])) {
            return $this->instances[$id];
        }

        // Create from factory
        if (isset($this->factories[$id])) {
            return ($this->factories[$id])($this);
        }

        // Create from shared
        if (isset($this->shared[$id])) {
            $instance = ($this->shared[$id])($this);
            $this->instances[$id] = $instance;
            return $instance;
        }

        // Auto-resolve class
        if (class_exists($id)) {
            return $this->resolve($id);
        }

        throw new Exception("Service not found: {$id}");
    }

    /**
     * Check if service exists
     */
    public function has(string $id): bool
    {
        return isset($this->instances[$id]) 
            || isset($this->shared[$id]) 
            || isset($this->factories[$id])
            || isset($this->aliases[$id])
            || class_exists($id);
    }

    /**
     * Auto-resolve a class with dependency injection
     * @throws ReflectionException
     */
    private function resolve(string $class): object
    {
        $reflector = new ReflectionClass($class);
        
        if (!$reflector->isInstantiable()) {
            throw new Exception("Class {$class} is not instantiable");
        }

        $constructor = $reflector->getConstructor();
        
        if ($constructor === null) {
            return new $class();
        }

        $dependencies = $this->resolveDependencies($constructor->getParameters());
        
        return $reflector->newInstanceArgs($dependencies);
    }

    /**
     * Resolve constructor dependencies
     * @throws ReflectionException
     */
    private function resolveDependencies(array $parameters): array
    {
        $dependencies = [];

        foreach ($parameters as $parameter) {
            $type = $parameter->getType();
            
            if ($type === null) {
                if ($parameter->isDefaultValueAvailable()) {
                    $dependencies[] = $parameter->getDefaultValue();
                } else {
                    throw new Exception("Cannot resolve parameter: {$parameter->getName()}");
                }
                continue;
            }

            $typeName = $type->getName();
            
            if ($type->isBuiltin()) {
                if ($parameter->isDefaultValueAvailable()) {
                    $dependencies[] = $parameter->getDefaultValue();
                } elseif ($type->allowsNull()) {
                    $dependencies[] = null;
                } else {
                    throw new Exception("Cannot resolve builtin type: {$typeName}");
                }
            } else {
                try {
                    $dependencies[] = $this->get($typeName);
                } catch (Exception $e) {
                    if ($parameter->isDefaultValueAvailable()) {
                        $dependencies[] = $parameter->getDefaultValue();
                    } elseif ($type->allowsNull()) {
                        $dependencies[] = null;
                    } else {
                        throw $e;
                    }
                }
            }
        }

        return $dependencies;
    }

    /**
     * Call a method with automatic dependency injection
     * @throws ReflectionException
     */
    public function call(object|array $callable, array $parameters = []): mixed
    {
        if (is_string($callable) && str_contains($callable, '@')) {
            [$class, $method] = explode('@', $callable);
            $instance = $this->get($class);
            $callable = [$instance, $method];
        }

        $reflection = new \ReflectionFunction(\Closure::fromCallable($callable));
        $dependencies = [];

        foreach ($reflection->getParameters() as $param) {
            $name = $param->getName();
            
            if (isset($parameters[$name])) {
                $dependencies[] = $parameters[$name];
                continue;
            }

            $type = $param->getType();
            
            if ($type && !$type->isBuiltin()) {
                $dependencies[] = $this->get($type->getName());
            } elseif ($param->isDefaultValueAvailable()) {
                $dependencies[] = $param->getDefaultValue();
            } elseif ($type && $type->allowsNull()) {
                $dependencies[] = null;
            } else {
                throw new Exception("Cannot resolve parameter: {$name}");
            }
        }

        return $reflection->invokeArgs(...$dependencies);
    }

    /**
     * Clear all instances
     */
    public function flush(): void
    {
        $this->instances = [];
    }
}
