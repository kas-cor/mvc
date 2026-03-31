<?php

namespace src\Middleware;

/**
 * Class AuthMiddleware
 * @package src\Middleware
 */
class AuthMiddleware implements MiddlewareInterface
{
    /**
     * Handle incoming request
     * @param array $request
     * @param callable $next
     * @return mixed
     */
    public function handle(array $request, callable $next)
    {
        // Check if user is authenticated
        if (!isset($_SESSION['user_id'])) {
            // Redirect to login or return 401
            if ($this->isApiRequest($request)) {
                http_response_code(401);
                echo json_encode(['error' => 'Unauthorized']);
                exit;
            }
            
            header('Location: /admin/login');
            exit;
        }
        
        return $next($request);
    }
    
    /**
     * Check if request is API request
     * @param array $request
     * @return bool
     */
    private function isApiRequest(array $request): bool
    {
        return isset($request['accept']) && strpos($request['accept'], 'application/json') !== false;
    }
}
