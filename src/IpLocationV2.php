<?php


namespace itbdw\Ip;

use itbdw\Ip\Parser\QQwry;
use itbdw\Ip\Parser\IpV6wry;

class IpLocationV2 implements IpLocationInterface
{
    private static $ipV4Path;
    private static $ipV6Path;

    public static function getLocation($ip, $ipV4Path='', $ipV6Path='') {

        //if  ipV4Path 记录位置
        if (strlen($ipV4Path)) {
            self::setIpV4Path($ipV4Path);
        }

        //if  ipV6Path 记录位置
        if (strlen($ipV6Path)) {
            self::setIpV6Path($ipV6Path);
        }

        $stringParser = new StringParser();

        if (self::isIpV4($ip)) {
            $ins = new QQwry();
            $ins->setDBPath(self::getIpV4Path());
            $location = $ins->getIp($ip);
            if (isset($location['error'])) {
                return $location;
            }
            return $stringParser->parse($location);
        } else if (self::isIpV6($ip)) {
            $ins = new IpV6wry();
            $ins->setDBPath(self::getIpV6Path());
            $location = $ins->getIp($ip);

            if (isset($location['error'])) {
                return $location;
            }

            return $stringParser->parse($location);
        } else {
            return [
                'error' => 'IP Invalid'
            ];
        }
    }

    public static function setIpV4Path($path)
    {
        self::$ipV4Path = $path;
    }

    public static function setIpV6Path($path)
    {
        self::$ipV6Path = $path;
    }

    private static function getIpV4Path() {
        return self::$ipV4Path ? : __DIR__ . '/files/qqwry.dat';
    }
    private static function getIpV6Path() {
        return self::$ipV6Path ? : __DIR__ . '/files/ipv6wry.db';
    }

    private static function isIpV4($ip) {
        return false !== filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4);
    }

    private static function isIpV6($ip) {
        return false !== filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6);
    }
}