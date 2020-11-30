<?php


namespace itbdw\Ip;

use itbdw\Ip\IpParser\QQwry;
use itbdw\Ip\IpParser\IpV6wry;

class IpLocation {
    private static $ipV4Path;
    private static $ipV6Path;

    /**
     * @param $ip
     * @param string $ipV4Path
     * @param string $ipV6Path
     * @return array
     */
    public static function getLocationWithoutParse($ip, $ipV4Path='', $ipV6Path='') {

        //if  ipV4Path 记录位置
        if (strlen($ipV4Path)) {
            self::setIpV4Path($ipV4Path);
        }

        //if  ipV6Path 记录位置
        if (strlen($ipV6Path)) {
            self::setIpV6Path($ipV6Path);
        }

        if (self::isIpV4($ip)) {
            $ins = new QQwry();
            $ins->setDBPath(self::getIpV4Path());
            $location = $ins->getIp($ip);
        } else if (self::isIpV6($ip)) {
            $ins = new IpV6wry();
            $ins->setDBPath(self::getIpV6Path());
            $location = $ins->getIp($ip);

        } else {
            $location = [
                'error' => 'IP Invalid'
            ];
        }

        return $location;
    }

    /**
     * @param $ip
     * @param string $ipV4Path
     * @param string $ipV6Path
     * @return array|mixed
     */
    public static function getLocation($ip, $ipV4Path='', $ipV6Path='') {
        $location = self::getLocationWithoutParse($ip, $ipV4Path, $ipV6Path);
        if (isset($location['error'])) {
            return $location;
        }
        return StringParser::parse($location);
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
        return self::$ipV4Path ? : __DIR__ . '/libs/qqwry.dat';
    }
    private static function getIpV6Path() {
        return self::$ipV6Path ? : __DIR__ . '/libs/ipv6wry.db';
    }

    private static function isIpV4($ip) {
        return false !== filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4);
    }

    private static function isIpV6($ip) {
        return false !== filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6);
    }
}