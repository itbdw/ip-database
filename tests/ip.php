<?php
/**
 * @author Zhao Binyan <zhao.binyan@immomo.com>
 * @since  2015-06-11
 */

//you do not need to do this if use composer!
require dirname(__DIR__) . '/src/Ip/IpLocation.php';

use itbdw\Ip\IpLocation;

$hostnames = [
    'qq.com',
    'baidu.com',
    '360.com',
    'immomo.com',
    'github.com',
    'sina.com.cn',
    'yungbo.com',
    'aliyun.com',
    'google.com',

];
shuffle($hostnames);
$hostname = array_pop($hostnames);
$ip       = gethostbyname($hostname);

$ipLocation = new IpLocation();

echo $hostname . "\n";

echo json_encode($ipLocation->getAddr($ip), JSON_UNESCAPED_UNICODE) . "\n";

