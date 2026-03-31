<?php

namespace src\Logger;

use Monolog\Logger as MonologLogger;
use Monolog\Handler\StreamHandler;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Formatter\LineFormatter;

/**
 * Class Logger
 * @package src\Logger
 */
class Logger
{
    /**
     * @var MonologLogger
     */
    private static ?MonologLogger $instance = null;
    
    /**
     * Get logger instance
     * @param string $channel
     * @return MonologLogger
     */
    public static function getInstance(string $channel = 'app'): MonologLogger
    {
        if (self::$instance === null) {
            self::$instance = new MonologLogger($channel);
            
            // Add rotating file handler
            $logPath = __DIR__ . '/../../logs/app.log';
            $dir = dirname($logPath);
            
            if (!is_dir($dir)) {
                mkdir($dir, 0755, true);
            }
            
            $fileHandler = new RotatingFileHandler($logPath, 7);
            $fileHandler->setFormatter(self::getFormatter());
            
            // Add error log handler for production
            if (env('APP_ENV') === 'production') {
                $errorHandler = new StreamHandler('php://stderr', MonologLogger::ERROR);
                $errorHandler->setFormatter(self::getFormatter());
                self::$instance->pushHandler($errorHandler);
            }
            
            self::$instance->pushHandler($fileHandler);
        }
        
        return self::$instance;
    }
    
    /**
     * Get log formatter
     * @return LineFormatter
     */
    private static function getFormatter(): LineFormatter
    {
        $format = "[%datetime%] %channel%.%level_name%: %message% %context%\n";
        return new LineFormatter($format, 'Y-m-d H:i:s');
    }
    
    /**
     * Log debug message
     * @param string $message
     * @param array $context
     * @return void
     */
    public static function debug(string $message, array $context = []): void
    {
        self::getInstance()->debug($message, $context);
    }
    
    /**
     * Log info message
     * @param string $message
     * @param array $context
     * @return void
     */
    public static function info(string $message, array $context = []): void
    {
        self::getInstance()->info($message, $context);
    }
    
    /**
     * Log warning message
     * @param string $message
     * @param array $context
     * @return void
     */
    public static function warning(string $message, array $context = []): void
    {
        self::getInstance()->warning($message, $context);
    }
    
    /**
     * Log error message
     * @param string $message
     * @param array $context
     * @return void
     */
    public static function error(string $message, array $context = []): void
    {
        self::getInstance()->error($message, $context);
    }
    
    /**
     * Log critical message
     * @param string $message
     * @param array $context
     * @return void
     */
    public static function critical(string $message, array $context = []): void
    {
        self::getInstance()->critical($message, $context);
    }
}
