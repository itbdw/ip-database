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

die("目前暂未实现解析民政部地区码");

date_default_timezone_set("PRC");

require dirname(__DIR__) . '/src/IpLocation.php';

//将民政部地区码地址粘贴进来

//将文件下载到本地

//解析地区码

$region_code_html = \itbdw\Ip\IpLocation::root('/tmp/mca_region_code.html');

// todo 解析 html 变成格式化的数据
