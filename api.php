<?php
/**
 * API的入口文件
 *
 * @file
 */

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
        try {
            $action = GunWiki\SiteInfo\API\APIFactory::make($item);
            $data += $action->exec();
        } catch (\LogicException $e) {
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
