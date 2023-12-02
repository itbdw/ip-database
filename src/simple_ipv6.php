<?php
namespace myapp;
class ip
{
    public static $_app;
    public function __construct($path = '')
    {
        # code...
        if (empty($path)) $path = __DIR__ . "/libs/";
        $this->path = $path;
        self::$_app = $this;
    }
    public static function app($path='')
    {
        # code...
        if (empty(self::$_app)) return new ip($path);
        return self::$_app;
    }
    public static function get($ip)
    {
        return self::app()->query($ip);
    }
    public static function validate($ip)
    {
        return filter_var($ip, FILTER_VALIDATE_IP) !== false;
    }
    // IPV6 转数字
    public static function ip2long($ip)
    {
        $hex = self::ip2hex($ip);
        if (function_exists('gmp_init')) {
            return gmp_strval(gmp_init($hex, 16), 10);
        } elseif (function_exists('bcadd')) {
            return self::hex2num($hex);
        } else {
            trigger_error('GMP or BCMATH extension not installed!', E_USER_ERROR);
        }
    }
    // 10-39位 数字转IPV6
    public static function long2ip($dec)
    {
        if (function_exists('gmp_init')) {
            $hex = gmp_strval(gmp_init($dec, 10), 16);
            $hex = str_pad($hex, strlen($hex) > 8 ? 32 : 8, '0', STR_PAD_LEFT);
        } elseif (function_exists('bcadd')) {
            $hex = self::num2hex($dec);
        } else {
            trigger_error('GMP or BCMATH extension not installed!', E_USER_ERROR);
        }
        return self::hex2ip($hex);
    }
    public static function hex2ip($hex)
    {
        $len = strlen($hex);
        $arr = str_split($hex, $len == 8 ? 2 : 4);
        if ($len == 8) $arr = array_map(fn ($v) => $v ? base_convert($v, 16, 10) : 0, $arr);
        $ip = implode($len == 8 ? '.' : ':', $arr);
        return $len == 8 ? $ip : inet_ntop(inet_pton($ip));
    }
    public static function ip2hex($ip)
    {
        return bin2hex(inet_pton($ip));
    }
    public static function hex2num($str, $split = 4)
    {
        $snum = strlen($str);
        if ($split > $snum) $split = $snum;
        $arr = array_map(fn ($v) => $v ? base_convert($v, 16, 10) : 0, str_split($str, $split));
        $num = 0;
        $len = count($arr) - 1;
        foreach ($arr as $k => $v) {
            $num = bcadd($num, $v ? bcmul($v, $len ? bcpow(16, ($len) * $split) : 1) : 0);
            $len--;
        }
        return $num;
    }
    public static function num2hex($num, $isip = false)
    {
        $snum = strlen($num);
        if ($snum < 2) $split = 1;
        else if ($snum < 3) $split = 2;
        else $split = 4;
        $arr = [1];
        for ($i = 1; $i != 0; $i++) {
            $p = bcpow(16, $i * $split);
            if (bccomp($p, $num) == 1) break;
            $arr[$i] = $p;
        }
        $hex = '';
        if (!empty($arr)) {
            $arr = array_reverse($arr);
            foreach ($arr as $k => $v) {
                $arr[$k] = bcdiv($num, $v, 0);
                $num = bcsub($num, bcmul($v, $arr[$k]));
            }
            $hex = implode('', array_map(fn ($v) => str_pad(base_convert($v, 10, 16), $split, '0', STR_PAD_LEFT), $arr));
        }

        if ($isip) {
            $iplen = strlen($hex);
            $arr = str_split($hex, $iplen == 8 ? 2 : 4);
            if ($iplen == 8) $arr = array_map(fn ($v) => $v ? base_convert($v, 16, 10) : 0, $arr);
            $hex = implode($iplen == 8 ? '.' : ':', $arr);
            return $iplen == 8 ? $hex : inet_ntop(inet_pton($hex));
        }
        return $hex;
    }
    public $fd;
    public $FORMAT = 'J2';
    public function query($ip)
    {
        if (empty($this->fd)) $this->get_fd();
        $hex = self::ip2hex($ip);
        $p1 = substr($hex, 0, strlen($hex) > 16 ? 16 : strlen($hex));
        $p2 = substr($hex, strlen($hex) > 16 ? 16 : 0, strlen($hex) > 16 ? 32 : strlen($hex));
        // IP地址前半部分转换成有int
        $ip_num1 = self::hex2num($p1);
        // IP地址后半部分转换成有int
        $ip_num2 = self::hex2num($p2);; //$ip_num_arr[2];
        /*
        echo $p1 . '<br>';
        echo $p2 . '<br>';
        if (strlen($hex) > 16) {
            $ip_bin = inet_pton($ip);
            $ip_num_arr = unpack($this->FORMAT, $ip_bin);
            print_r($ip_num_arr);
        }
        echo "\n<br>" . $ip_num1 . "<br>\n";
        echo "\n<br>" . $ip_num2 . "<br>\n";
        echo bcsub($ip_num1, $ip_num2);
        */
        $ip_find = $this->find($ip_num1, $ip_num2, 0, $this->total);
        $ip_offset = $this->index_start_offset + $ip_find * ($this->iplen + $this->offlen);
        $ip_offset2 = $ip_offset + $this->iplen + $this->offlen;
        $ip_start = inet_ntop(pack($this->FORMAT, $this->read($ip_offset, 8, 1), 0));
        try {
            $ip_end = inet_ntop(pack($this->FORMAT, $this->read($ip_offset2, 8, 1) - 1, 0));
        } catch (\RuntimeException $e) {
            $ip_end = "FFFF:FFFF:FFFF:FFFF::";
        }
        echo $ip_offset;
        $ip_record_offset = $this->read($ip_offset + $this->iplen, $this->offlen, 1);
        $ip_addr = $this->read_record($ip_record_offset);
        $ip_addr_disp = $ip_addr[0] . " " . $ip_addr[1];
        return ["start" => $ip_start, "end" => $ip_end, "addr" => $ip_addr, "disp" => $ip_addr_disp];
    }
    public function get_fd()
    {
        $this->fd = fopen($this->path . 'ipv6wry.db', 'rb');
        $this->index_start_offset = $this->read(16, 8, 1);
        $this->offlen = $this->read(6);
        $this->iplen = $this->read(7);
        $this->total = $this->read(8, 8, 1);
        $this->index_end_offset = $this->index_start_offset
            + ($this->iplen + $this->offlen) * $this->total;
        $this->has_initialized = true;
        register_shutdown_function(array($this, 'fd_close'));
    }
    public function fd_close()
    {
        fclose($this->fd);
    }
    /**
     * 读取记录
     * @param $fd
     * @param $offset
     * @return string[]
     */
    public function read_record($offset)
    {
        $record = [0 => "", 1 => ""];
        $flag = $this->read($offset);
        if ($flag == 1) {
            $location_offset = $this->read($offset + 1, $this->offlen, 8, 1);
            return $this->read_record($location_offset);
        }
        $record[0] = $this->read_location($offset);
        if ($flag == 2) {
            $record[1] = $this->read_location($offset + $this->offlen + 1);
        } else {
            $record[1] = $this->read_location($offset + strlen($record[0]) + 1);
        }
        return $record;
    }

    /**
     * 读取地区
     * @param $fd
     * @param $offset
     * @return string
     */
    public function read_location($offset)
    {
        if ($offset == 0) {
            return "";
        }
        $flag = $this->read($offset);
        // 出错
        if ($flag == 0) {
            return "";
        }
        // 仍然为重定向
        if ($flag == 2) {
            $offset = $this->read($offset + 1, $this->offlen, 1);
            return $this->read_location($offset);
        }
        return $this->readstr($offset);
    }
    /**
     * 查找 ip 所在的索引
     * @param $fd
     * @param $ip_num1
     * @param $ip_num2
     * @param $l
     * @param $r
     * @return mixed
     */
    public function find($ip_num1, $ip_num2, $l, $r)
    {
        if ($l + 1 >= $r) {
            return $l;
        }
        $m = intval(($l + $r) / 2);
        $m_ip1 = $this->read(
            $this->index_start_offset + $m * ($this->iplen + $this->offlen),
            $this->iplen,
            1
        );
        $m_ip2 = 0;
        if ($this->iplen <= 8) {
            $m_ip1 <<= 8 * (8 - $this->iplen);
        } else {
            $m_ip2 = $this->read(
                $this->index_start_offset + $m * ($this->iplen + $this->offlen) + 8,
                $this->iplen - 8,
                1
            );
            $m_ip2 <<= 8 * (16 - $this->iplen);
        }
        if ($this->uint64cmp($ip_num1, $m_ip1) < 0) {
            return $this->find($ip_num1, $ip_num2, $l, $m);
        }
        if ($this->uint64cmp($ip_num1, $m_ip1) > 0) {
            return $this->find($ip_num1, $ip_num2, $m, $r);
        }
        if ($this->uint64cmp($ip_num2, $m_ip2) < 0) {
            return $this->find($ip_num1, $ip_num2, $l, $m);
        }
        return $this->find($ip_num1, $ip_num2, $m, $r);
    }

    public function readstr($offset = null)
    {
        if (!is_null($offset)) {
            fseek($this->fd, $offset);
        }
        $str = "";
        $chr = $this->read($offset);
        while ($chr != 0) {
            $str .= chr($chr);
            $offset++;
            $chr = $this->read($offset);
        }
        return $str;
    }
    public static function uint64cmp($a, $b)
    {
        if ($a >= 0 && $b >= 0 || $a < 0 && $b < 0) {
            return $a <=> $b;
        }
        if ($a >= 0 && $b < 0) {
            return -1;
        }
        return 1;
    }
    public function read($offset, $size = 1, $raw = 0)
    {
        if (!is_null($offset)) {
            fseek($this->fd, $offset);
        }
        $a = fread($this->fd, $size);
        if ($raw) $a = str_pad($a, $size + 8, "\0", STR_PAD_RIGHT);
        return @unpack($raw ? "P" : "C", $a)[1];
    }
}
