<?php
/**
 * Created by PhpStorm.
 * User: zhao.binyan
 * Date: 2019/7/25
 * Time: 下午2:24
 */

die("Not Available.");

/*
		纯真数据库自动更新原理实现
		www.shuax.com 2014.03.27
	*/

/**
 * 示例
 * `php ~/bin/update-ip.php`
 *
 * 更新到指定目录
 * `php ~/bin/update-ip.php -d=/tmp`
 */


date_default_timezone_set("PRC");

function curls_get($url)
{
//    $header = ["User-Agent: Mozilla/3.0 (compatible; Indy Library)", "Host: update.cz88.net"];
    $header = ["User-Agent: Mozilla/3.0 (compatible; Indy Library)", "Host: update.cz88.net", "Accept: text/html, /"];

    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT => 600,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_HTTPHEADER => $header,
        CURLOPT_URL => $url,
    ));
    $res = curl_exec($curl);

    if (curl_errno($curl)) {
        error_log("curl error " . curl_errno($curl) . ' ' . curl_error($curl));
    }

    curl_close($curl);
    return $res;
}

//可设置为服务器特定目录，单独，避免组件升级互相影响
$dir = dirname(__DIR__) . "/src/libs";
$option = getopt("d::");
if (isset($option['d'])) {
    if (!is_readable($option['d'])) {
        die("bad param, dir not readable " . $option['d']);
    }
    $dir = $option['d'];
}

$stime = microtime(true);

echo "开始准备更新数据库" . date("Y-m-d H:i:s");
echo "\n";

$copywrite = curls_get("http://update.cz88.net/ip/copywrite.rar");

if (!$copywrite) {
    $download_spend = $qqwry_time - $stime;
    die("copywrite.rar 下载失败 " . sprintf("下载耗时%s", $download_spend));
}


$qqwry      = curls_get("https://update.cz88.net/ip/qqwry.rar");
$qqwry_time = microtime(true);

if (!$qqwry) {
    $download_spend = $qqwry_time - $stime;
    die("qqwry.rar 下载失败 " . sprintf("下载耗时%s", $download_spend));
}

$key = unpack("V6", $copywrite)[6];
for ($i = 0; $i < 0x200; $i++) {
    $key *= 0x805;
    $key++;
    $key       = $key & 0xFF;
    $qqwry[$i] = chr(ord($qqwry[$i]) ^ $key);
}
$qqwry      = gzuncompress($qqwry);
$unzip_time = microtime(true);

$download_spend = $qqwry_time - $stime;
$unzip_spend    = $unzip_time - $qqwry_time;

if (!$qqwry) {
    die("gzip 解压缩失败 " . sprintf("下载耗时%s，解压耗时%s", $download_spend, $unzip_spend));
}

$tmp_file    = $dir . '/' . 'qqwry.dat.bak';
$online_file = $dir . '/' . 'qqwry.dat';

if (file_put_contents($tmp_file, $qqwry)) {
    $put_time  = microtime(true);
    $put_spend = $put_time - $unzip_time;
    copy($online_file, $online_file.'.online.bak');
    copy($tmp_file, $online_file);

    $copy_spend = microtime(true) - $put_time;
    die("更新成功 " . sprintf("下载耗时%s，解压耗时%s，写入耗时%s，复制耗时%s", $download_spend, $unzip_spend, $put_spend, $copy_spend));
} else {
    die("更新失败 " . sprintf("下载耗时%s，解压耗时%s", $download_spend, $unzip_spend));
}
