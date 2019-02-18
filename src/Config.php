<?php

namespace GunWiki\SiteInfo;

class Config
{
    private static $instance;

    private $vars = [];

    public static function getInstance() : self
    {
        if (self::$instance === null) {
            self::$instance = new self;
        }
        return self::$instance;
    }

    private function __construct()
    {
        require 'config.php';
        $vars = get_defined_vars();
        foreach ($vars as $varName => $varValue) {
            if (substr($varName, 0, 3) === 'cfg') {
                $this->vars[substr($varName, 3)] = $varValue;
            }
        }
    }

    public function has(string $key) : bool
    {
        return isset($this->vars[$key]);
    }

    /**
     * @param string $key
     * @return mixed
     * @throws \Exception
     */
    public function get(string $key)
    {
        if (!isset($this->vars[$key])) {
            throw new \Exception("Undefined config key: $key");
        }
        return $this->vars[$key];
    }
}
