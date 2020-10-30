<?php

namespace itbdw\Ip\Parser;

interface IpParserInterface
{
    function setDBPath($filePath);

    function getIp($ip);
}