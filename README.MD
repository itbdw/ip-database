## 说明

这套识别程序的数据库是免费IP数据库、IP离线地址库。支持将IP转化为结构化的国家、省、市、县、运营商、地区信息)

0，该数据库识别是离线的。
1，该数据库基于纯真IP库，IP地址纠错相关和最新地址获取请直接去纯真官网，下面有给出地址。
2，纯真IP识别算法来源网络。
3，结构化程序来自我2012年的脑洞。

纯真IP数据存储时，并不是结构化的，因此解析出来有误差在所难免，国内 ip 可以识别出省份，基本可以识别出市。运营商、县数据看运气。

## 使用说明


当前版本为3.x（ipv4+ipv6），如需要2.x稳定版请访问  https://github.com/itbdw/ip-database/tree/2.x

目前3.x无缝兼容2.x版本，理论上可直接升级，但需做好验证。


```
composer require 'itbdw/ip-database' ^3.0
```


```php

//根据实际情况，基本上用框架（如 Laravel）的话不需要手动引入
//require 'vendor/autoload.php';

use itbdw\Ip\IpLocation;

//0配置使用
echo json_encode(IpLocation::getLocation($ip), JSON_UNESCAPED_UNICODE) . "\n";

//支持自定义文件路径
$qqwry_filepath = '/abspath/qqwry.dat';
$ipv6wry_path = '/abspath/ipv6wry.db';
echo json_encode(IpLocation::getLocation($ip, $qqwry_filepath), JSON_UNESCAPED_UNICODE) . "\n";


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


## 本地测试测试

```
cd 进入 ip-database 目录 ,composer install

php tests/ip.php

{"ip":"172.217.25.14","country":"美国","province":"","city":"","county":"","area":"美国 Google全球边缘网络","isp":""}
{"ip":"140.205.172.5","country":"中国","province":"上海","city":"","county":"","area":"中国上海 阿里云","isp":""}
{"ip":"123.125.115.110","country":"中国","province":"北京","city":"","county":"","area":"中国北京 北京百度网讯科技有限公司联通节点(BGP)","isp":"联通"}
{"ip":"221.196.0.0","country":"中国","province":"天津","city":"河北区","county":"","area":"中国天津河北区 联通","isp":"联通"}
{"ip":"60.195.153.98","country":"中国","province":"北京","city":"顺义区","county":"","area":"中国北京顺义区 后沙峪金龙网吧","isp":""}
{"ip":"218.193.183.35","country":"中国","province":"上海","city":"","county":"","area":"中国上海 D27-707","isp":""}
{"ip":"210.74.2.227","country":"中国","province":"北京","city":"","county":"","area":"中国北京 实验学院机房","isp":""}
{"ip":"162.105.217.0","country":"中国","province":"北京","city":"","county":"","area":"中国北京 4区-4f","isp":""}
{"ip":"fe80:0000:0001:0000:0440:44ff:1233:5678","country":"局域网","province":"","city":"","county":"","area":"局域网 本地链路单播地址","isp":""}
{"ip":"2409:8900:103f:14f:d7e:cd36:11af:be83","country":"中国","province":"北京","city":"","county":"","area":"中国北京 中国移动CMNET网络","isp":"移动"}

php tests/ip.php -i 58.196.128.0
{"ip":"58.196.128.0","country":"中国","province":"上海","city":"","county":"","area":"中国上海 上海交通大学","isp":""}

php tests/ip.php -i 2409:8a00:6c1d:81c0:51b4:d603:57d1:b5ec
{"ip":"2409:8a00:6c1d:81c0:51b4:d603:57d1:b5ec","country":"中国","province":"北京","city":"","county":"","area":"中国北京 中国移动公众宽带","isp":"移动"}

```

## 提高下载速度
建议腾讯云加速 https://mirrors.cloud.tencent.com/help/composer.html

原因 https://github.com/itbdw/ip-database/issues/42

## 赞助喝口水
这个项目也是多个日夜思考的结果，如果觉得对你有帮助，小手一抖也是感谢的。
<div>
  <div style="float:left;border:solid 1px 000;margin:2px;">
    <img src="https://wx1.sinaimg.cn/mw690/6b94a2e5ly1gl0wztevpxj20yi1aujwb.jpg"  width="200" height="260" >
  </div>
</div>

## 手动更新离线包
1，纯真IP库（需要安装 EXE ，解压获得离线包）
https://www.cz88.net/help?id=free

2，IPV6（目前已经不再提供离线下载包）
https://ip.zxinc.org/ipquery/
