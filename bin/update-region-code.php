<?php
/**
 * Created by PhpStorm.
 * User: zhao.binyan
 * Date: 2019/7/25
 * Time: 下午2:24
 */


/**
 * 示例
 * `php ~/bin/update-region-code.php`
 *
 */

date_default_timezone_set("PRC");

require dirname(__DIR__) . '/src/IpLocation.php';

$region_code_html = \itbdw\Ip\IpLocation::root('/tmp/mca_region_code.html');

// todo 解析 html 变成格式化的数据
