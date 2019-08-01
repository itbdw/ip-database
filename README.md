# 免费IP数据库 (纯真IP库，已经格式为国家、省、市、县、运营商)

此工具基于纯真 IP 库，并且把非结构化的数据结构化。

一旦识别了 IP，都可以显示国家。国内 ip 都能识别出省，基本可以识别出市、运营商，有部分能识别出县，以及公司小区学校网吧等信息。

## 环境要求

只需要 php 环境即可本地解析 ip。
不需要网络请求。
第一次查询即读入内存，以后直接从内存读取，效率非常高。

## 数据库文件更新日期

2019年7月25日更新，数据库为2019年7月20日版本

## 使用说明

```
composer require 'itbdw/ip-database'
```

```php

//根据实际情况，基本上用框架（如 Laravel）的话不需要手动引入
//require 'vendor/autoload.php';

use itbdw\Ip\IpLocation;

//支持自定义文件路径
$qqwry_filepath = '/abspath/qqwry.dat';
echo json_encode(IpLocation::getLocation($ip, $qqwry_filepath), JSON_UNESCAPED_UNICODE) . "\n";
echo json_encode(IpLocation::getLocation($ip), JSON_UNESCAPED_UNICODE) . "\n";

```

## 响应

获取成功
```json
{
  "ip": "163.177.65.160",
  "country": "中国",
  "province": "广东",
  "city": "深圳市",
  "county": "",
  "isp": "联通",
  "area": "中国广东省深圳市腾讯计算机系统联通节点"
}
```

异常
```json
{
  "error": "ip invalid"
}
```


## 测试

```
php tests/ip.php

php tests/ip.php -i 58.196.128.0 

```

## 典型返回
```
{"ip":"172.217.25.14","country":"美国","province":"","city":"","county":"","isp":"","area":"美国加利福尼亚州圣克拉拉县山景市谷歌公司"}
{"ip":"140.205.172.5","country":"中国","province":"浙江","city":"杭州市","county":"","isp":"","area":"中国浙江杭州市阿里巴巴网络有限公司BGP数据中心"}
{"ip":"123.125.115.110","country":"中国","province":"北京","city":"","county":"","isp":"联通","area":"中国北京北京百度网讯科技有限公司联通节点(BGP)"}
{"ip":"221.196.0.0","country":"中国","province":"天津","city":"河北区","county":"","isp":"联通","area":"中国天津河北区联通"}
{"ip":"60.195.153.98","country":"中国","province":"北京","city":"顺义区","county":"","isp":"","area":"中国北京顺义区后沙峪金龙网吧"}
```

## 更新数据库

### 在线直接更新

更新到源码目录
`php ~/bin/update-ip.php`

更新到指定目录
`php ~/bin/update-ip.php -d /tmp`

### 【或者】自己手动更新数据库

1，http://www.cz88.net/fox/ipdat.shtml
下载数据库程序（Windows 环境），执行完毕后，即可在程序安装目录找到数据库文件 qqwry.dat

2，复制到 src 目录，覆盖掉原文件即可；或者，把文件同步到服务器特定路径，但这种方式要求调用方法时传入
 qqwry.dat 的绝对路径。

## Thanks

+ 1, qqwry.dat database provider http://www.cz88.net/fox/ipdat.shtml
+ 2, class original provider 马秉尧


## update log

```
 IP 地理位置查询类
 
 2019-07-25 赵彬言         1，增加自动更新功能，参考 https://blog.shuax.com/archives/QQWryUpdate.html 感谢 https://github.com/itbdw/ip-database/issues/10
 
 2017-09-12 赵彬言         1，缩减返回数据，去掉字段 remark smallarea beginip endip
                          2，将调用改为单例模式，保证只读取一次文件
                          3，修复 bug，直接将返回 gbk 编码内容转为 utf-8，移除编码隐患
                          4，去掉了"省"标志，变成了如 中国 浙江 杭州市 这样的数据

 2017-09-04 赵彬言         1，更新 composer 相对路径,bug fix

 2015-06-11 赵彬言         1，支持composer 方式引用
                          2，更新 is_valid_ip 实现

 2013-11-10 赵彬言         1，优化，新增支持到市区，县城
                          2，返回结构增加 smallarea，具体请看注释

 2012-10-21 赵彬言         1，增加市，县显示
                          2，去掉不靠谱的自动转码
                             先统一改为 GBK，最后再做转换解决编码问题

 2012-08-15 赵彬言         1，更新为 PHP5 的规范
                          2，增加 wphp_ip2long 方法
                          3，增加 get_province 方法
                          4，增加 get_isp 方法
                          5，增加 is_valid_ip 方法

```

## 其它 IP 数据库推荐

国内的

http://www.ipip.net/index.html

国际的

https://dev.maxmind.com/zh-hans/geoip/geoip2/geolite2-%E5%BC%80%E6%BA%90%E6%95%B0%E6%8D%AE%E5%BA%93/


