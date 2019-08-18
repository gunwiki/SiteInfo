<?php

namespace GunWiki\SiteInfo\Cache;

/**
 * 只从这个缓存工厂获得缓存实例
 * @package GunWiki\SiteInfo\Cache
 */
class CacheFactory
{
    private static $instance;

    private $caches = [
        'local' => []
    ];

    private function __construct()
    {
    }

    public static function getInstance() : self
    {
        if (self::$instance === null) {
            self::$instance = new self;
        }
        return self::$instance;
    }

    public function getLocalCache(string $filename) : Cache
    {
        if (!isset($this->caches['local'][$filename])) {
            $this->caches['local'][$filename] = new JsonCache($filename);
        }
        return $this->caches['local'][$filename];
    }
}
