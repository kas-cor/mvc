<?php

namespace src\Middleware;

/**
 * Class RateLimitMiddleware
 * @package src\Middleware
 */
class RateLimitMiddleware implements MiddlewareInterface
{
    /**
     * @var int Maximum requests per window
     */
    private int $maxRequests;
    
    /**
     * @var int Time window in seconds
     */
    private int $window;
    
    /**
     * Constructor
     * @param int $maxRequests
     * @param int $window
     */
    public function __construct(int $maxRequests = 60, int $window = 60)
    {
        $this->maxRequests = $maxRequests;
        $this->window = $window;
    }
    
    /**
     * Handle incoming request
     * @param array $request
     * @param callable $next
     * @return mixed
     */
    public function handle(array $request, callable $next)
    {
        $clientId = $this->getClientId();
        $key = 'rate_limit_' . $clientId;
        
        $now = time();
        $data = $_SESSION[$key] ?? ['count' => 0, 'reset' => $now + $this->window];
        
        // Reset if window expired
        if ($now >= $data['reset']) {
            $data = ['count' => 0, 'reset' => $now + $this->window];
        }
        
        $data['count']++;
        $_SESSION[$key] = $data;
        
        // Check if limit exceeded
        if ($data['count'] > $this->maxRequests) {
            http_response_code(429);
            header('Retry-After: ' . ($data['reset'] - $now));
            echo json_encode([
                'error' => 'Too Many Requests',
                'retry_after' => $data['reset'] - $now
            ]);
            exit;
        }
        
        return $next($request);
    }
    
    /**
     * Get client identifier
     * @return string
     */
    private function getClientId(): string
    {
        return $_SERVER['HTTP_X_FORWARDED_FOR'] 
            ?? $_SERVER['REMOTE_ADDR'] 
            ?? 'unknown';
    }
}
