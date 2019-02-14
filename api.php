<?php
/**
 * API的入口文件
 *
 * @file
 */

require 'config.php';
require __DIR__ . '/vendor/autoload.php';

if (isset($_GET['action'])) {
    switch ($_GET['action']) {
        case 'viewcount':
            getViewCount();
        default:
            http_response_code(510);
            echo json_encode([
                'error' => [
                    'action' => 'Unsupported action'
                ]
            ]);
            die;
    }
} else {
    http_response_code(510);
    echo json_encode([
        'error' => [
            'action' => 'No action parameter provided'
        ],
    ]);
    die;
}

function getViewCount() {
    global $cfgAccessLogPath;
    $file = new SplFileObject($cfgAccessLogPath, 'r');
    $app = new GunWiki\SiteInfo\AnalysisSiteAccessLog($file);
    $res['ViewCount'] = $app->run();
    echo json_encode($res);
}

