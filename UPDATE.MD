## update log



```
IP 地理位置查询类

2021-04-10 赵彬言         

1，3.x 支持ipv6，无缝衔接2.x

3.x已完成的功能有

- 支持ipv6
- 2.x 平滑升级

计划完成的功能有

- 解析民政部地区码，以便返回更加精确的城市信息；同时可携带其他信息，如地区码、经纬度等



2020-10-30 赵彬言         

1，大更新，重构代码。兼容历史代码。希望能支持ipv6

2019-07-25 赵彬言         

1，增加自动更新功能，参考 https://blog.shuax.com/archives/QQWryUpdate.html 感谢 https://github.com/itbdw/ip-database/issues/10

2017-09-12 赵彬言         

1，缩减返回数据，去掉字段 remark smallarea beginip endip
2，将调用改为单例模式，保证只读取一次文件
3，修复 bug，直接将返回 gbk 编码内容转为 utf-8，移除编码隐患
4，去掉了"省"标志，变成了如 中国 浙江 杭州市 这样的数据

2017-09-04 赵彬言         

1，更新 composer 相对路径,bug fix

2015-06-11 赵彬言         

1，支持composer 方式引用
2，更新 is_valid_ip 实现

2013-11-10 赵彬言         

1，优化，新增支持到市区，县城
2，返回结构增加 smallarea，具体请看注释

2012-10-21 赵彬言         

1，增加市，县显示
2，去掉不靠谱的自动转码
先统一改为 GBK，最后再做转换解决编码问题

2012-08-15 赵彬言         

1，更新为 PHP5 的规范
2，增加 wphp_ip2long 方法
3，增加 get_province 方法
4，增加 get_isp 方法
5，增加 is_valid_ip 方法

```