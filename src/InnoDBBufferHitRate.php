<?php

namespace GunWiki\SiteInfo;

class InnoDBBufferHitRate
{
    private $mysql;

    public function __construct(string $dbHost, string $dbUsername, string $dbPassword)
    {
        $mysql = new \mysqli($dbHost, $dbUsername, $dbPassword);
        if ($mysql->connect_error) {
            die('Connect Error (' . $mysqli->connect_errno . ') '
            . $mysqli->connect_error);
        }
        $this->mysql = $mysql;
    }

    public function get() : float
    {
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
        return ($requestCount - $hitCount) / $requestCount; 
    }
}
