<?php

namespace GunWiki\SiteInfo\Cache;

interface Cache
{
    public function get(string $key) :? string;

    public function set(string $key, string $value);

    public function unset(string $key) : void;

    public function has(string $key) : bool;

    public function cleanAll() : void;
}
