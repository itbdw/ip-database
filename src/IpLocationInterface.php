<?php


namespace itbdw\Ip;


interface IpLocationInterface
{
    public static function getLocation($ip);

    public static function setIpV4Path($path);

    public static function setIpV6Path($path);

}