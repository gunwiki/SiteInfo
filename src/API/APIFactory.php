<?php

namespace GunWiki\SiteInfo\API;

class APIFactory
{
    const MAP = [
        'viewcount' => 'ViewCount',
        'innodbinfo' => 'InnoDBInfo',
    ];

    public static function make(string $key) : IAPI
    {
        if (!isset(self::MAP[$key])) {
            throw new \LogicException("Undefined API module: $key");
        }
        $classname = __NAMESPACE__ . '\\' . self::MAP[$key];
        return new $classname;
    }
}
