<?php
/**
 * API的入口文件
 *
 * @file
 */

require 'config.php';
require __DIR__ . '/vendor/autoload.php';

if (isset($_GET['action'])) {
    $data = routeRequest($_GET);
    sendResponse($data);
} else {
    http_response_code(510);
    echo json_encode([
        'error' => [
            'action' => 'No action parameter provided'
        ],
    ]);
    die;
}

/**
 * 路由请求
 * @return array 返回获取到的数据
 */
function routeRequest(array $request) : array
{
    $data = [];
    if (strpos($request['action'], '|') === false) {
        $action = [$request['action']];
    } else {
        $action = explode('|', $request['action']);
    }
    foreach ($action as $item) {
        switch ($item) {
            case 'viewcount':
                $data += getViewCount();
                break;
            case 'innodbinfo':
                $data += getInnoDBBufferHitRate();
                break;
            default:
                $data['error'][$item] = 'Unsupported action';
        }
    }
    return $data;
}

/**
 * 发送响应
 * @param array $data
 * @param int $httpStatusCode
 */
function sendResponse(array $data, int $httpStatusCode = 200) : void
{
    http_response_code($httpStatusCode);
    echo json_encode($data);
    die;
}

function getViewCount() : array
{
    global $cfgAccessLogPath;
    $file = new SplFileObject($cfgAccessLogPath, 'r');
    $app = new GunWiki\SiteInfo\AnalysisSiteAccessLog($file);
    $res['ViewCount'] = $app->run();
    return $res;
}

function getInnoDBBufferHitRate() : array
{
    global $cfgDBHost, $cfgDBUsername, $cfgDBPassword;
    $app = new GunWiki\SiteInfo\InnoDBBufferHitRate($cfgDBHost, $cfgDBUsername, $cfgDBPassword);
    return [
        'InnoDBBufferHitRate' => $app->get(),
    ];
}

