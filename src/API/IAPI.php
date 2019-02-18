<?php
/**
 * 所有API模块的公共接口
 *
 * @file
 */

namespace GunWiki\SiteInfo\API;

interface IAPI
{
    /**
     * 执行API模块
     * @return array 返回执行结果
     */
    public function exec() : array;
}