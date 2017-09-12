<?php
/**
 * @author Zhao Binyan <itbudaoweng@gmail.com>
 * @since  2015-06-11
 */

//you do not need to do this if use composer!
require dirname(__DIR__) . '/src/IpLocation.php';

use itbdw\Ip\IpLocation;

$hostnames = [
    'aliyun.com',
    'google.com',
    'weibo.com',
    'invalidip',
];
shuffle($hostnames);
$hostname = array_pop($hostnames);

$ip       = gethostbyname($hostname);

echo $hostname . "\n";

echo json_encode(IpLocation::getLocation($ip), JSON_UNESCAPED_UNICODE) . "\n";

