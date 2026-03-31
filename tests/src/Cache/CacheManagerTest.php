<?php

namespace tests\src\Cache;

use PHPUnit\Framework\TestCase;
use src\Cache\CacheManager;

/**
 * Class CacheManagerTest
 * @package tests\src\Cache
 */
class CacheManagerTest extends TestCase
{
    protected function tearDown(): void
    {
        // Clear cache after each test
        CacheManager::clear();
    }
    
    public function testCacheSetAndGet()
    {
        $result = CacheManager::set('test_key', 'test_value');
        
        $this->assertTrue($result);
        $this->assertEquals('test_value', CacheManager::get('test_key'));
    }
    
    public function testCacheGetWithDefault()
    {
        $value = CacheManager::get('nonexistent_key', 'default_value');
        
        $this->assertEquals('default_value', $value);
    }
    
    public function testCacheHas()
    {
        CacheManager::set('exists_key', 'value');
        
        $this->assertTrue(CacheManager::has('exists_key'));
        $this->assertFalse(CacheManager::has('nonexistent_key'));
    }
    
    public function testCacheDelete()
    {
        CacheManager::set('delete_key', 'value');
        $this->assertTrue(CacheManager::has('delete_key'));
        
        $result = CacheManager::delete('delete_key');
        
        $this->assertTrue($result);
        $this->assertFalse(CacheManager::has('delete_key'));
    }
    
    public function testCacheSetMultiple()
    {
        $values = [
            'key1' => 'value1',
            'key2' => 'value2',
            'key3' => 'value3',
        ];
        
        $result = CacheManager::setMultiple($values);
        
        $this->assertTrue($result);
        
        $retrieved = CacheManager::getMultiple(['key1', 'key2', 'key3']);
        
        $this->assertEquals('value1', $retrieved['key1']);
        $this->assertEquals('value2', $retrieved['key2']);
        $this->assertEquals('value3', $retrieved['key3']);
    }
    
    public function testCacheClear()
    {
        CacheManager::set('clear_key1', 'value1');
        CacheManager::set('clear_key2', 'value2');
        
        $result = CacheManager::clear();
        
        $this->assertTrue($result);
        $this->assertFalse(CacheManager::has('clear_key1'));
        $this->assertFalse(CacheManager::has('clear_key2'));
    }
    
    public function testCacheExpiration()
    {
        CacheManager::set('expire_key', 'value', 1); // 1 second TTL
        
        $this->assertEquals('value', CacheManager::get('expire_key'));
        
        sleep(2); // Wait for expiration
        
        $this->assertNull(CacheManager::get('expire_key'));
    }
}
