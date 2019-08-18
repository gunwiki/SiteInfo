<?php

namespace GunWiki\SiteInfo\API;

use GunWiki\SiteInfo\Config;

class InnoDBInfo implements IAPI
{
    /**
     * @var \mysqli
     */
    private $mysql;

    public function exec(): array
    {
        $this->openDBConn();
        $res = $this->mysql->query('show status like \'Innodb_buffer_pool_read%\'');
        foreach ($res as $value) {
            if ($value['Variable_name'] === 'Innodb_buffer_pool_read_requests') {            $requestCount = $value['Value'];
                continue;
            }
            if ($value['Variable_name'] === 'Innodb_buffer_pool_reads') {
                $hitCount = $value['Value'];
                continue;
            }
        }
        $this->mysql->close();
        return ['InnoDBBufferHitRate' => ($requestCount - $hitCount) / $requestCount];
    }

    private function openDBConn()
    {
        $config = Config::getInstance();
        $mysql = new \mysqli($config->get('DBHost'), $config->get('DBUsername'), $config->get('DBPassword'));
        if ($mysql->connect_error) {
            die('Connect Error (' . $mysql->connect_errno . ') '
                . $mysql->connect_error);
        }
        $this->mysql = $mysql;
    }
}
