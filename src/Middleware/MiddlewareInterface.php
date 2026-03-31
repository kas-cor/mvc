<?php

namespace src\Middleware;

/**
 * Interface MiddlewareInterface
 * @package src\Middleware
 */
interface MiddlewareInterface
{
    /**
     * Handle incoming request
     * @param array $request
     * @param callable $next
     * @return mixed
     */
    public function handle(array $request, callable $next);
}
