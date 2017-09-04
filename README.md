# ip-database

## IP-database, runs with PHP.
This is a fairly simple way to get the city info by a IP address.

## Why choose a local ip-database
+ Simple To Run. You just need to set up a web server that runs PHP. Simple, right?
+ Easy To Use. You just need to new the class and get the result. Wow!
+ Really Fast. The data structure is well designed, you get the result without waiting.

## Usage
composer supported

```
composer require 'itbdw/ip-database'
```

```php
use itbdw\IpLocation;

$hostname = 'itbdw.com';
$ip       = gethostbyname($hostname);

$ipLocation = new IpLocation();

echo $hostname . "\n";

echo json_encode($ipLocation->getAddr($ip), JSON_UNESCAPED_UNICODE) . "\n";
```


## test case, so cool!

```
➜  ip-database git:(develop) php tests/ip.php
qq.com
```
case ok
```javascript
{
  "ip": "163.177.65.160",
  "beginip": "163.177.65.0",
  "endip": "163.177.65.255",
  "country": "中国",
  "province": "广东省",
  "city": "深圳市",
  "county": "",
  "isp": "联通",
  "remark": "广东省深圳市",
  "smallarea": "腾讯计算机系统联通节点",
  "area": "中国广东省深圳市腾讯计算机系统联通节点"
}
```

case error
```javascript
{
  "error": "ip invalid"
}
```

##Thanks
+ 1, qqary.dat database provider www.cz88.net
+ 2, class original provider 马秉尧


## update log
```
 IP 地理位置查询类

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


 此类基于 马秉尧 先生的 1.5 版本，在此感谢。目前您看到的这个文件是由赵彬言维护的。

 升级ip数据库非常方便，如果用的composer方式安装的，直接composer update 即可

 如果你想自己升级，可以安装纯真ip库软件，先对 ip.exe 进行升级
 然后将目录下的 qqwry.dat 文件复制过来覆盖掉旧的文件即可。
```

