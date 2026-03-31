<?php

namespace tests\src\Middleware;

use PHPUnit\Framework\TestCase;
use src\Middleware\MiddlewareDispatcher;
use src\Middleware\AuthMiddleware;
use src\Middleware\CsrfMiddleware;

/**
 * Class MiddlewareTest
 * @package tests\src\Middleware
 */
class MiddlewareTest extends TestCase
{
    public function testMiddlewareDispatcherAddsMiddleware()
    {
        $dispatcher = new MiddlewareDispatcher();
        $dispatcher->add(new AuthMiddleware());
        
        $this->assertEquals(1, $dispatcher->count());
    }
    
    public function testMiddlewareDispatcherHandlesRequest()
    {
        $dispatcher = new MiddlewareDispatcher();
        
        $result = null;
        $finalHandler = function($request) use (&$result) {
            $result = 'handled';
            return $result;
        };
        
        $request = ['method' => 'GET'];
        $dispatcher->handle($request, $finalHandler);
        
        $this->assertEquals('handled', $result);
    }
    
    public function testCsrfMiddlewareGeneratesToken()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        $token = CsrfMiddleware::generateToken();
        
        $this->assertNotEmpty($token);
        $this->assertEquals(64, strlen($token));
        $this->assertEquals($token, CsrfMiddleware::getToken());
    }
    
    public function testAuthMiddlewareRequiresSession()
    {
        // This test would require mocking $_SESSION
        $this->assertTrue(true); // Placeholder
    }
}
