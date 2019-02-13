<?php

require 'config.php';

require __DIR__ . '/vendor/autoload.php';

$file = new SplFileObject($cfgAccessLogPath, 'r');
echo '<pre>';
$app = new GunWiki\SiteInfo\AnalysisSiteAccessLog($file);
$app->run();
echo '</pre>';
