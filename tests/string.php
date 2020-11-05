<?php
/**
 * @author Zhao Binyan <itbudaoweng@gmail.com>
 * @since  2015-06-11
 */

//you do not need to do this if use composer!
use itbdw\Ip\IpLocation;
use itbdw\Ip\StringParser;

require dirname(__DIR__) . '/src/IpLocation.php';
require dirname(__DIR__) . '/src/StringParser.php';

$in = IpLocation::getIn();


$ips = [
    "172.217.25.14",//美国
    "140.205.172.5",//杭州
    "123.125.115.110",//北京
    "221.196.0.0",//
    "60.195.153.98",

    //bug ip 都是涉及到直辖市的
    "218.193.183.35", //"province":"上海交通大学闵行校区",
    "210.74.2.227", //,"province":"北京工业大学","city":"",
    "162.105.217.0", //,"province":"北京大学万柳学区","ci
];

$obj = new StringParser();
$location = [
    'country' => '中国',
    'area' => '',
];

foreach ($ips as $ip) {
    $location = $in->getOriginalLocation($ip);

    var_dump($location);
    var_dump($obj->parse($location));

}

//var_dump($obj->parse($location));


