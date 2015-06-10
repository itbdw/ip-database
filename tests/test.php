<?php
/**
 *
 * @author Zhao Binyan <zhao.binyan@immomo.com>
 * @since  2015-06-11
 */

//you do not need to do this if use composer!
require dirname(__DIR__) . '/src/IpLocation/IpLocation.php';

use itbdw\IpLocation\IpLocation;

$ip = gethostbyname('qq.com');

$ipLocation = new IpLocation();
var_dump($ipLocation->getAddr($ip));
