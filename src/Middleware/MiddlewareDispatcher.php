<?php

namespace src\Middleware;

/**
 * Class MiddlewareDispatcher
 * @package src\Middleware
 */
class MiddlewareDispatcher
{
    /**
     * @var array Middleware stack
     */
    private array $middleware = [];
    
    /**
     * Add middleware to stack
     * @param MiddlewareInterface|string $middleware
     * @return self
     */
    public function add($middleware): self
    {
        $this->middleware[] = $middleware;
        return $this;
    }
    
    /**
     * Handle request through middleware stack
     * @param array $request
     * @param callable $finalHandler
     * @return mixed
     */
    public function handle(array $request, callable $finalHandler)
    {
        $handler = $finalHandler;
        
        // Build middleware chain (reverse order)
        foreach (array_reverse($this->middleware) as $middleware) {
            $instance = is_string($middleware) ? new $middleware() : $middleware;
            
            if (!$instance instanceof MiddlewareInterface) {
                throw new \InvalidArgumentException(
                    'Middleware must implement MiddlewareInterface'
                );
            }
            
            $handler = fn($req) => $instance->handle($req, $handler);
        }
        
        return $handler($request);
    }
    
    /**
     * Clear all middleware
     * @return self
     */
    public function clear(): self
    {
        $this->middleware = [];
        return $this;
    }
    
    /**
     * Get middleware count
     * @return int
     */
    public function count(): int
    {
        return count($this->middleware);
    }
}
