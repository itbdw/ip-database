# ip-database

## PHP IP 地址工具
只需要 php 环境即可本地解析 ip，不需要网络请求，非常快

## Usage

```
composer require 'itbdw/ip-database'
```

```php
use itbdw\Ip\IpLocation;

$hostname = 'itbdw.com';
$ip       = gethostbyname($hostname);

echo $hostname . "\n";

echo json_encode(IpLocation::getLocation($ip), JSON_UNESCAPED_UNICODE) . "\n";
```

## 数据库文件更新

由于我目前没有 Windows 电脑，所以IP文件已经很久没有更新了。

http://www.cz88.net/fox/ipdat.shtml
下载数据库程序（Windows 环境），执行完毕后，将 QQWry.dat 文件重命名为 qqwry.dat 放到对应目录即可

## test case, so cool!

```
➜  php tests/ip.php
qq.com
```
case ok
```json
{
  "ip": "163.177.65.160",
  "country": "中国",
  "province": "广东省",
  "city": "深圳市",
  "county": "",
  "isp": "联通",
  "area": "中国广东省深圳市腾讯计算机系统联通节点"
}
```

case error
```json
{
  "error": "ip invalid"
}
```

##Thanks
+ 1, qqary.dat database provider http://www.cz88.net/fox/ipdat.shtml
+ 2, class original provider 马秉尧


## update log
```
 IP 地理位置查询类
 
 2017-09-12 赵彬言         1，缩减返回数据，去掉字段 remark smallarea baginip endip
                          2，将调用改为单例模式，保证只读取一次文件
                          3，修复 bug，直接将返回 gbk 编码内容转为 utf-8，移除编码隐患

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
http://lite.ip2location.com/


