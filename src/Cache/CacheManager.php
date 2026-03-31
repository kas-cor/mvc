<?php

namespace src\Cache;

use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Component\Cache\Adapter\RedisAdapter;
use Symfony\Component\Cache\Adapter\ArrayAdapter;
use Psr\Cache\CacheItemPoolInterface;

/**
 * Class CacheManager
 * @package src\Cache
 */
class CacheManager
{
    /**
     * @var CacheItemPoolInterface|null
     */
    private static ?CacheItemPoolInterface $instance = null;
    
    /**
     * Get cache instance
     * @param string $driver
     * @param int $lifetime
     * @param string $namespace
     * @return CacheItemPoolInterface
     */
    public static function getInstance(
        string $driver = 'filesystem',
        int $lifetime = 3600,
        string $namespace = 'mvc_cache'
    ): CacheItemPoolInterface {
        if (self::$instance === null) {
            switch ($driver) {
                case 'redis':
                    $redisDsn = env('REDIS_DSN', 'redis://localhost:6379');
                    self::$instance = new RedisAdapter(
                        \Symfony\Component\Cache\Adapter\AbstractAdapter::createConnection($redisDsn),
                        $lifetime,
                        $namespace
                    );
                    break;
                    
                case 'array':
                    self::$instance = new ArrayAdapter($lifetime);
                    break;
                    
                case 'filesystem':
                default:
                    $cachePath = __DIR__ . '/../../var/cache';
                    if (!is_dir($cachePath)) {
                        mkdir($cachePath, 0755, true);
                    }
                    self::$instance = new FilesystemAdapter($namespace, $lifetime, $cachePath);
                    break;
            }
        }
        
        return self::$instance;
    }
    
    /**
     * Get item from cache
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public static function get(string $key, $default = null)
    {
        $item = self::getInstance()->getItem($key);
        return $item->isHit() ? $item->get() : $default;
    }
    
    /**
     * Set item in cache
     * @param string $key
     * @param mixed $value
     * @param int $ttl
     * @return bool
     */
    public static function set(string $key, $value, int $ttl = null): bool
    {
        $item = self::getInstance()->getItem($key);
        $item->set($value);
        
        if ($ttl !== null) {
            $item->expiresAfter($ttl);
        }
        
        return self::getInstance()->save($item);
    }
    
    /**
     * Delete item from cache
     * @param string $key
     * @return bool
     */
    public static function delete(string $key): bool
    {
        return self::getInstance()->deleteItem($key);
    }
    
    /**
     * Clear all cache
     * @return bool
     */
    public static function clear(): bool
    {
        return self::getInstance()->clear();
    }
    
    /**
     * Check if key exists in cache
     * @param string $key
     * @return bool
     */
    public static function has(string $key): bool
    {
        $item = self::getInstance()->getItem($key);
        return $item->isHit();
    }
    
    /**
     * Get multiple items from cache
     * @param array $keys
     * @return array
     */
    public static function getMultiple(array $keys): array
    {
        $items = self::getInstance()->getItems($keys);
        $result = [];
        
        foreach ($items as $key => $item) {
            $result[$key] = $item->isHit() ? $item->get() : null;
        }
        
        return $result;
    }
    
    /**
     * Set multiple items in cache
     * @param array $values
     * @param int $ttl
     * @return bool
     */
    public static function setMultiple(array $values, int $ttl = null): bool
    {
        $cache = self::getInstance();
        $saved = true;
        
        foreach ($values as $key => $value) {
            $item = $cache->getItem($key);
            $item->set($value);
            
            if ($ttl !== null) {
                $item->expiresAfter($ttl);
            }
            
            $saved = $cache->save($item) && $saved;
        }
        
        return $saved;
    }
}
