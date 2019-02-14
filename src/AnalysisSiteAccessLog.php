<?php

namespace GunWiki\SiteInfo;

use GunWiki\SiteInfo\LogModel\ViewParser;

class AnalysisSiteAccessLog
{
    const PATTERN = '#^(?<IP>([0-9]{1,3}\.){3}[0-9]{1,3})\s-\s-\s\[(?<time>[0-9]{1,2}/\w*/[0-9]{4}:[0-9]{2}:[0-9]{2}:'.
        '[0-9]{2}\s\+[0-9]{4})\]\s"(?<method>[A-Z]{3,4})\s(?<URL>.*)\s(?<version>HTTP/[0-9]\.[0-9])"\s'.
        '(?<statusCode>[0-9]{3})\s(?<size>[0-9]*)(\s(?<refer>".*")\s(?<UA>".*")$)?#';

    const NEEDLE = [
        '/wiki',
        '/w/index.php',
    ];

    /**
     * @var \SplFileObject
     */
    private $file;

    public function __construct(\SplFileObject $file)
    {
        $this->file = $file;
    }

    public function run()
    {
        $hit = [];
        foreach ($this->file as $line) {
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
                $date = date('Y-m-d', $view->getTime());
                $this->hit($hit, $date);
            }
        }
        return $hit;
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

    private function hit(array &$hit, string $date)
    {
        if (isset($hit[$date])) {
            $hit[$date]++;
        } else {
            $hit[$date] = 1;
        }
    }
}
