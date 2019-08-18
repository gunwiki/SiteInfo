<?php

use GunWiki\SiteInfo\Cache\CacheFactory;
use PHPUnit\Framework\TestCase;

/**
 * @covers \GunWiki\SiteInfo\Cache\JsonCache
 */
class JsonCacheTest extends TestCase
{
    public function testAddItem()
    {
        $cache = CacheFactory::getInstance()->getLocalCache('test');
        $cache->set('1', 'Hello World');
        $this->assertSame('Hello World', $cache->get('1'));
        unlink('test');
    }
}
