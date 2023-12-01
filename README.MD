## 说明

这套识别程序的数据库是免费IP数据库、IP离线地址库。支持将IP转化为结构化的国家、省、市、县、运营商、地区信息)

0，该数据库识别是离线的。

1，该数据库基于纯真IP库，IP地址纠错相关和最新地址获取请直接去纯真官网，下面有给出地址。

2，纯真IP识别算法来源网络。

3，结构化程序来自我2012年的脑洞。

纯真IP已从03年开始存在多年且一直免费，实属不易。因为数据存储时，并不是结构化的，因此有误差在所难免。这个结构化程序，国内 ip 可以识别出省份，基本可以识别出市。运营商、县数据看运气。

## 环境要求
PHP5即可，本地安装，无网络依赖，只需要 php 环境即可本地解析 ip。

## 使用说明

当前版本为2.x 稳定版，仅支持ipv4，以后不再增加新功能。如需要ipv6，可访问 https://github.com/itbdw/ip-database/tree/3.x

```
composer require itbdw/ip-database ^2.0
```

```php

//用框架（如 Laravel）不需要手动引入
//require 'vendor/autoload.php';

use itbdw\Ip\IpLocation;

//直接用附带的版本
echo json_encode(IpLocation::getLocation($ip), JSON_UNESCAPED_UNICODE) . "\n";

//支持自定义文件路径
$qqwry_filepath = '/abspath/qqwry.dat';
echo json_encode(IpLocation::getLocation($ip, $qqwry_filepath), JSON_UNESCAPED_UNICODE) . "\n";


```

## 响应

成功
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

## 典型返回示例
```
{"ip":"172.217.25.14","country":"美国","province":"","city":"","county":"","isp":"","area":"美国加利福尼亚州圣克拉拉县山景市谷歌公司"}
{"ip":"140.205.172.5","country":"中国","province":"浙江","city":"杭州市","county":"","isp":"","area":"中国浙江杭州市阿里巴巴网络有限公司BGP数据中心"}
{"ip":"123.125.115.110","country":"中国","province":"北京","city":"","county":"","isp":"联通","area":"中国北京北京百度网讯科技有限公司联通节点(BGP)"}
{"ip":"221.196.0.0","country":"中国","province":"天津","city":"河北区","county":"","isp":"联通","area":"中国天津河北区联通"}
{"ip":"60.195.153.98","country":"中国","province":"北京","city":"顺义区","county":"","isp":"","area":"中国北京顺义区后沙峪金龙网吧"}
```

## 更新数据库（不可用）



## 赞助喝口水
这个项目也是多个日夜思考的结果，如果觉得对你有帮助，小手一抖也是感谢的。
<div>
  <div style="float:left;border:solid 1px 000;margin:2px;">
    <img src="https://wx1.sinaimg.cn/mw690/6b94a2e5ly1gl0wztevpxj20yi1aujwb.jpg"  width="200" height="260" >
  </div>
</div>

## 感谢
1，纯真IP库 具体见 https://www.cz88.net/help?id=free

   如有商用，请去官网获得授权。谢谢

## 更新日志

```
 IP 地理位置查询类
 
 2023-11-23         升级 数据库版本 20231122
 
 2021-04-10         1，3.x 支持ipv6，无缝衔接2.x
 
 2020-11-07          1，只是更新文档，更新数据库。无它。自动更新暂时无法使用。
 
 2019-07-25          1，增加自动更新功能，参考 https://blog.shuax.com/archives/QQWryUpdate.html 感谢 https://github.com/itbdw/ip-database/issues/10
 
 2017-09-12          1，缩减返回数据，去掉字段 remark smallarea beginip endip
                          2，将调用改为单例模式，保证只读取一次文件
                          3，修复 bug，直接将返回 gbk 编码内容转为 utf-8，移除编码隐患
                          4，去掉了"省"标志，变成了如 中国 浙江 杭州市 这样的数据

 2017-09-04          1，更新 composer 相对路径,bug fix

 2015-06-11          1，支持composer 方式引用
                          2，更新 is_valid_ip 实现

 2013-11-10          1，优化，新增支持到市区，县城
                          2，返回结构增加 smallarea，具体请看注释

 2012-10-21          1，增加市，县显示
                          2，去掉不靠谱的自动转码
                             先统一改为 GBK，最后再做转换解决编码问题

 2012-08-15          1，更新为 PHP5 的规范
                          2，增加 wphp_ip2long 方法
                          3，增加 get_province 方法
                          4，增加 get_isp 方法
                          5，增加 is_valid_ip 方法

```

