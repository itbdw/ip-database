<?php


namespace itbdw\Ip;


class StringParser
{
    /**
     * 运营商词典
     *
     * @var array
     */
    private $dict_isp = [
        '联通',
        '移动',
        '铁通',
        '电信',
        '长城',
        '聚友',
    ];

    /**
     * 中国直辖市
     *
     * @var array
     */
    private $dict_city_directly = [
        '北京',
        '天津',
        '重庆',
        '上海',
    ];

    /**
     * 中国省份
     *
     * @var array
     */
    private $dict_province = [
        '北京',
        '天津',
        '重庆',
        '上海',
        '河北',
        '山西',
        '辽宁',
        '吉林',
        '黑龙江',
        '江苏',
        '浙江',
        '安徽',
        '福建',
        '江西',
        '山东',
        '河南',
        '湖北',
        '湖南',
        '广东',
        '海南',
        '四川',
        '贵州',
        '云南',
        '陕西',
        '甘肃',
        '青海',
        '台湾',
        '内蒙古',
        '广西',
        '宁夏',
        '新疆',
        '西藏',
        '香港',
        '澳门',
    ];

    /**
     * $location = [
     *  'country', 'area'
     * ];
     *
     * @param $location
     * @return mixed
     */
    public function parse($location)
    {
        $result = [];
        $is_china = false;
        $seperator_sheng = '省';
        $seperator_shi = '市';
        $seperator_xian = '县';
        $seperator_qu = '区';

        if (!$location) {
            $result['error'] = 'file open failed';
            return $result;
        }

        $location['org_country'] = $location['country']; //北京市朝阳区

        $location['org_area'] = $location['area']; // 金桥国际小区

        $location['province'] = $location['city'] = $location['county'] = '';

        $_tmp_province = explode($seperator_sheng, $location['country']);
        //存在 省 标志 xxx省yyyy 中的yyyy
        if (isset($_tmp_province[1])) {
            $is_china = true;
            //省
            $location['province'] = $_tmp_province[0]; //河北

            if (strpos($_tmp_province[1], $seperator_shi) !== false) {
                $_tmp_city = explode($seperator_shi, $_tmp_province[1]);
                //市
                $location['city'] = $_tmp_city[0] . $seperator_shi;

                //县
                if (isset($_tmp_city[1])) {
                    if (strpos($_tmp_city[1], $seperator_xian) !== false) {
                        $_tmp_county = explode($seperator_xian, $_tmp_city[1]);
                        $location['county'] = $_tmp_county[0] . $seperator_xian;
                    }

                    //区
                    if (!$location['county'] && strpos($_tmp_city[1], $seperator_qu) !== false) {
                        $_tmp_qu = explode($seperator_qu, $_tmp_city[1]);
                        $location['county'] = $_tmp_qu[0] . $seperator_qu;
                    }
                }
            }
        } else {
            //处理内蒙古不带省份类型的和直辖市
            foreach ($this->dict_province as $key => $value) {

                if (false !== strpos($location['country'], $value)) {
                    $is_china = true;
                    //直辖市
                    if (in_array($value, $this->dict_city_directly)) {
                        $_tmp_province = explode($seperator_shi, $location['country']);

                        // 上海市浦江区xxx
                        if ($_tmp_province[0] == $value) {
                            //直辖市
                            $location['province'] = $_tmp_province[0];

                            //市辖区
                            if (isset($_tmp_province[1])) {
                                if (strpos($_tmp_province[1], $seperator_qu) !== false) {
                                    $_tmp_qu = explode($seperator_qu, $_tmp_province[1]);
                                    $location['city'] = $_tmp_qu[0] . $seperator_qu;
                                }
                            }
                        } else {
                            //上海交通大学
                            $location['province'] = $value;
                            $location['org_area'] = $location['org_country'] . $location['org_area'];
                        }
                    } else {
                        //省
                        $location['province'] = $value;

                        //没有省份标志 只能替换
                        $_tmp_city = str_replace($location['province'], '', $location['country']);

                        //防止直辖市捣乱 上海市xxx区 =》 市xx区
                        $_tmp_shi_pos = mb_stripos($_tmp_city, $seperator_shi);
                        if ($_tmp_shi_pos === 0) {
                            $_tmp_city = mb_substr($_tmp_city, 1);
                        }

                        //内蒙古 类型的 获取市县信息
                        if (strpos($_tmp_city, $seperator_shi) !== false) {
                            //市
                            $_tmp_city = explode($seperator_shi, $_tmp_city);

                            $location['city'] = $_tmp_city[0] . $seperator_shi;

                            //县
                            if (isset($_tmp_city[1])) {
                                if (strpos($_tmp_city[1], $seperator_xian) !== false) {
                                    $_tmp_county = explode($seperator_xian, $_tmp_city[1]);
                                    $location['county'] = $_tmp_county[0] . $seperator_xian;
                                }

                                //区
                                if (!$location['county'] && strpos($_tmp_city[1], $seperator_qu) !== false) {
                                    $_tmp_qu = explode($seperator_qu, $_tmp_city[1]);
                                    $location['county'] = $_tmp_qu[0] . $seperator_qu;
                                }
                            }
                        }
                    }

                    break;
                }
            }
        }

        if ($is_china) {
            $location['country'] = '中国';
        }

        $location['isp'] = $this->getIsp($location['area']);

        $result['ip'] = $location['ip'];

//            $result['beginip']   = $location['beginip'];
//            $result['endip']     = $location['endip'];

//            $result['org_country']    = $location['org_country'];  //纯真数据库返回的列1
//            $result['org_area'] = $location['org_area'];

        $result['country'] = $location['country'];
        $result['province'] = $location['province'];
        $result['city'] = $location['city'];
        $result['county'] = $location['county'];
        $result['isp'] = $location['isp'];

        $result['area'] = $location['country'] . $location['province'] . $location['city'] . $location['county'] . $location['org_area'];

        return $result;
    }


    /**
     * @param $str
     * @return string
     */
    private function getIsp($str)
    {
        $ret = '';

        foreach ($this->dict_isp as $k => $v) {
            if (false !== strpos($str, $v)) {
                $ret = $v;
                break;
            }
        }

        return $ret;
    }

}