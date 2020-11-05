<?php


namespace itbdw\Ip;

class IpLocationV2 implements IpLocationInterface
{
    public static function getLocation($ip, $ipV4Path='', $ipV6Path='') {

        //if  ipV4Path 记录位置
        if (strlen($ipV4Path)) {
            self::setIpV4Path($ipV4Path);
        }

        //if  ipV6Path 记录位置
        if (strlen($ipV6Path)) {
            self::setIpV6Path($ipV6Path);
        }
    }

    public static function setIpV4Path($path)
    {
        // TODO: Implement setIpV4Path() method.
    }

    public static function setIpV6Path($path)
    {
        // TODO: Implement setIpV6Path() method.
    }
}