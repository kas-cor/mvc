<?php

namespace src\Middleware;

/**
 * Class SecurityHeadersMiddleware
 * @package src\Middleware
 */
class SecurityHeadersMiddleware implements MiddlewareInterface
{
    /**
     * @var array Security headers
     */
    private array $headers = [
        'X-Frame-Options' => 'DENY',
        'X-Content-Type-Options' => 'nosniff',
        'X-XSS-Protection' => '1; mode=block',
        'Referrer-Policy' => 'strict-origin-when-cross-origin',
        'Permissions-Policy' => 'geolocation=(), microphone=(), camera=()',
    ];
    
    /**
     * Constructor
     * @param array $customHeaders
     */
    public function __construct(array $customHeaders = [])
    {
        $this->headers = array_merge($this->headers, $customHeaders);
        
        // Add CSP if not set
        if (!isset($this->headers['Content-Security-Policy'])) {
            $this->headers['Content-Security-Policy'] = 
                "default-src 'self'; script-src 'self' 'unsafe-inline' https://cdn.jsdelivr.net; style-src 'self' 'unsafe-inline' https://fonts.googleapis.com; font-src 'self' https://fonts.gstatic.com; img-src 'self' data: https:;";
        }
    }
    
    /**
     * Handle incoming request
     * @param array $request
     * @param callable $next
     * @return mixed
     */
    public function handle(array $request, callable $next)
    {
        // Set security headers on response
        foreach ($this->headers as $header => $value) {
            header("$header: $value");
        }
        
        // Force HTTPS in production
        if (env('APP_ENV') === 'production' && empty($_SERVER['HTTPS'])) {
            if ($_SERVER['REQUEST_METHOD'] === 'GET') {
                header('Location: https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
                exit;
            } else {
                http_response_code(403);
                echo 'HTTPS Required';
                exit;
            }
        }
        
        return $next($request);
    }
}
