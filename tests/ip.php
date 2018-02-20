<?php
/**
 * @author Zhao Binyan <itbudaoweng@gmail.com>
 * @since  2015-06-11
 */

//you do not need to do this if use composer!
require dirname(__DIR__) . '/src/IpLocation.php';

use itbdw\Ip\IpLocation;

$hostnames = [
    'google.com',
    'weibo.com',
    'nothing',
];
shuffle($hostnames);
$hostname = array_pop($hostnames);

$ip       = gethostbyname($hostname);

echo $hostname . "\n";

$qqwry_path = dirname(__DIR__) . '/src/qqwry.dat';

echo json_encode(IpLocation::getLocation($ip), JSON_UNESCAPED_UNICODE) . "\n";
echo json_encode(IpLocation::getLocation($ip, $qqwry_path), JSON_UNESCAPED_UNICODE) . "\n";

