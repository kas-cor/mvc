<?php

namespace src\Middleware;

/**
 * Class CsrfMiddleware
 * @package src\Middleware
 */
class CsrfMiddleware implements MiddlewareInterface
{
    /**
     * Token key in session
     */
    private const TOKEN_KEY = 'csrf_token';
    
    /**
     * Handle incoming request
     * @param array $request
     * @param callable $next
     * @return mixed
     */
    public function handle(array $request, callable $next)
    {
        // Only check on POST, PUT, DELETE requests
        if (in_array($_SERVER['REQUEST_METHOD'], ['POST', 'PUT', 'DELETE'])) {
            $token = $_POST['csrf_token'] ?? $_SERVER['HTTP_X_CSRF_TOKEN'] ?? null;
            
            if (!$this->validateToken($token)) {
                http_response_code(403);
                echo json_encode(['error' => 'Invalid CSRF token']);
                exit;
            }
        }
        
        return $next($request);
    }
    
    /**
     * Validate CSRF token
     * @param string|null $token
     * @return bool
     */
    private function validateToken(?string $token): bool
    {
        if (!$token || !isset($_SESSION[self::TOKEN_KEY])) {
            return false;
        }
        
        return hash_equals($_SESSION[self::TOKEN_KEY], $token);
    }
    
    /**
     * Generate new CSRF token
     * @return string
     */
    public static function generateToken(): string
    {
        $token = bin2hex(random_bytes(32));
        $_SESSION[self::TOKEN_KEY] = $token;
        return $token;
    }
    
    /**
     * Get current CSRF token
     * @return string|null
     */
    public static function getToken(): ?string
    {
        return $_SESSION[self::TOKEN_KEY] ?? null;
    }
}
