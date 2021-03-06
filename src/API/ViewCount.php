<?php

namespace GunWiki\SiteInfo\API;

use GunWiki\SiteInfo\Cache\CacheFactory;
use GunWiki\SiteInfo\Config;
use GunWiki\SiteInfo\LogModel\ViewParser;

class ViewCount implements IAPI
{
    private const CACHE_PATH = __DIR__ . '/../../cache/main.cache';

    const PATTERN = '#^(?<IP>([0-9]{1,3}\.){3}[0-9]{1,3})\s-\s-\s\[(?<time>[0-9]{1,2}/\w*/[0-9]{4}:[0-9]{2}:[0-9]{2}:'.
    '[0-9]{2}\s\+[0-9]{4})\]\s"(?<method>[A-Z]{3,4})\s(?<URL>.*)\s(?<version>HTTP/[0-9]\.[0-9])"\s'.
    '(?<statusCode>[0-9]{3})\s(?<size>[0-9]*)(\s(?<refer>".*")\s(?<UA>".*")$)?#';

    const NEEDLE = [
        '/wiki',
        '/w/index.php?title=',
    ];

    public function exec() : array
    {
        $cache = CacheFactory::getInstance()->getLocalCache(self::CACHE_PATH);
        if ($cache->has('viewcount')) {
            if (time() - $cache->get('viewcount-time') < 60 * 30) {
                return json_decode($cache->get('viewcount'), true);
            }
        }
        $file = new \SplFileObject(Config::getInstance()->get('AccessLogPath'));
        $hit = [];
        foreach ($file as $line) {
            if (empty($line)) {
                continue;
            }
            try {
                $view = (new ViewParser($line, self::PATTERN))->getResult();
            } catch (\RuntimeException $e) {
                continue;
            }
            $url = $view->getURL();
            if ($this->matchURL($url)) {
                // 过滤所有非200状态码的请求
                if ($view->getHttpCode() !== 200) {
                    continue;
                }
                $date = date('Y-m-d', $view->getTime());
                $this->hit($hit, $date, $view->getIP());
            }
        }
        $cache->set('viewcount', json_encode(['ViewCount' => $hit]));
        $cache->set('viewcount-time', time());
        return ['ViewCount' => $hit];
    }

    private function matchURL(string $url) : bool
    {
        foreach (self::NEEDLE as $needle) {
            if (strpos($url, $needle) !== false) {
                return true;
            }
        }
        return false;
    }

    private function hit(array &$hit, string $date, string $ip)
    {
        static $tmpDate;
        static $ips;
        $canPlus = false;
        if ($tmpDate === $date) {
            if (!isset($ips[$ip])) {
                $canPlus = true;
                $ips[$ip] = true;
            }
        } else {
            $tmpDate = $date;
            $ips = null;
            $ips[$ip] = true;
            $canPlus = true;
        }
        if (isset($hit[$date])) {
            $hit[$date]['viewcount']++;
            if ($canPlus) {
                $hit[$date]['ipcount']++;
            }
        } else {
            $hit[$date] = [
                'viewcount' => 1,
                'ipcount' => 1
            ];
        }
    }
}
