<?php

namespace itbdw\Ip\Parser;

interface IpParserInterface
{
    function setDBPath($filePath);

    /**
     * @param $ip
     * @return mixed ['ip', 'country', 'area']
     */
    function getIp($ip);
}