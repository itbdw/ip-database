<?php


namespace itbdw\Ip;


class StringParser
{
    /**
     * 运营商词典
     *
     * @var array
     */
    private $dictIsp = [
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
    private $dictCityDirectly = [
        '北京',
        '天津',
        '重庆',
        '上海',
    ];

    private $dictDistrictBlackTails = [
        '校区',
        '学区',
    ];

    /**
     * 中国省份
     *
     * @var array
     */
    private $dictProvince = [
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
     * 'ip', 'beginip', 'endip'
     * ];
     *
     * @param $location
     * @return mixed
     */
    public function parse($location)
    {
        $org = $location;
        $result = [];
        $isChina = false;
        $separatorProvince = '省';
        $separatorCity = '市';
        $separatorCounty = '县';
        $separatorDistrict = '区';

        if (!$location) {
            $result['error'] = 'file open failed';
            return $result;
        }

        $location['org_country'] = $location['country']; //北京市朝阳区

        $location['org_area'] = $location['area']; // 金桥国际小区

        $location['province'] = $location['city'] = $location['county'] = '';

        $_tmp_province = explode($separatorProvince, $location['country']);
        //存在 省 标志 xxx省yyyy 中的yyyy
        if (isset($_tmp_province[1])) {
            $isChina = true;
            //省
            $location['province'] = $_tmp_province[0]; //河北

            if (strpos($_tmp_province[1], $separatorCity) !== false) {
                $_tmp_city = explode($separatorCity, $_tmp_province[1]);
                //市
                $location['city'] = $_tmp_city[0] . $separatorCity;

                //县
                if (isset($_tmp_city[1])) {
                    if (strpos($_tmp_city[1], $separatorCounty) !== false) {
                        $_tmp_county = explode($separatorCounty, $_tmp_city[1]);
                        $location['county'] = $_tmp_county[0] . $separatorCounty;
                    }

                    //区
                    if (!$location['county'] && strpos($_tmp_city[1], $separatorDistrict) !== false) {
                        $_tmp_qu = explode($separatorDistrict, $_tmp_city[1]);
                        $location['county'] = $_tmp_qu[0] . $separatorDistrict;
                    }
                }
            }
        } else {
            //处理内蒙古不带省份类型的和直辖市
            foreach ($this->dictProvince as $key => $value) {

                if (false !== strpos($location['country'], $value)) {
                    $isChina = true;
                    $location['province'] = $value;

                    //直辖市
                    if (in_array($value, $this->dictCityDirectly)) {

                        //直辖市
                        $_tmp_province = explode($value, $location['country']);

                        //市辖区
                        if (isset($_tmp_province[1])) {

                            $_tmp_province[1] = $this->lTrim($_tmp_province[1], $separatorCity);


                            if (strpos($_tmp_province[1], $separatorDistrict) !== false) {
                                $_tmp_qu = explode($separatorDistrict, $_tmp_province[1]);

                                //解决 休息休息校区 变成城市区域
                                $isHitBlackTail = false;
                                foreach ($this->dictDistrictBlackTails as $blackTail) {
                                    //尾
                                    if (mb_substr($_tmp_qu[0], -mb_strlen($blackTail)) == $blackTail) {
                                        $isHitBlackTail = true;
                                        break;
                                    }
                                }

                                //校区，学区
                                if ((!$isHitBlackTail) && mb_strlen($_tmp_qu[0]) < 5) {
                                    //有点尴尬
                                    $location['city'] = $_tmp_qu[0] . $separatorDistrict;
                                }
                            }
                        }
                    } else {

                        //没有省份标志 只能替换
                        $_tmp_city = str_replace($location['province'], '', $location['country']);

                        //防止直辖市捣乱 上海市xxx区 =》 市xx区
                        $_tmp_city = $this->lTrim($_tmp_city, $separatorCity);

                        //内蒙古 类型的 获取市县信息
                        if (strpos($_tmp_city, $separatorCity) !== false) {
                            //市
                            $_tmp_city = explode($separatorCity, $_tmp_city);

                            $location['city'] = $_tmp_city[0] . $separatorCity;

                            //县
                            if (isset($_tmp_city[1])) {
                                if (strpos($_tmp_city[1], $separatorCounty) !== false) {
                                    $_tmp_county = explode($separatorCounty, $_tmp_city[1]);
                                    $location['county'] = $_tmp_county[0] . $separatorCounty;
                                }

                                //区
                                if (!$location['county'] && strpos($_tmp_city[1], $separatorDistrict) !== false) {
                                    $_tmp_qu = explode($separatorDistrict, $_tmp_city[1]);
                                    $location['county'] = $_tmp_qu[0] . $separatorDistrict;
                                }
                            }
                        }
                    }

                    break;
                }
            }
        }

        if ($isChina) {
            $location['country'] = '中国';
        }

//        $location['isp'] = $this->getIsp($location['area']);

        $result['ip'] = $location['ip'];

//            $result['beginip']   = $location['beginip'];
//            $result['endip']     = $location['endip'];
//
//        $result['org_country']    = $location['org_country'];  //纯真数据库返回的列1
//        $result['org_area'] = $location['org_area'];

        $result['country'] = $location['country'];
        $result['province'] = $location['province'];
        $result['city'] = $location['city'];
        $result['county'] = $location['county'];

        $result['area'] = $location['country'] . $location['province'] . $location['city'] . $location['county'] . $location['org_area'];

        $result['isp'] = $this->getIsp($result['area']);

        $result['org'] = $org;

        return $result;
    }


    /**
     * @param $str
     * @return string
     */
    private function getIsp($str)
    {
        $ret = '';

        foreach ($this->dictIsp as $k => $v) {
            if (false !== strpos($str, $v)) {
                $ret = $v;
                break;
            }
        }

        return $ret;
    }

    private function lTrim($word, $w) {
        $pos = mb_stripos($word, $w);
        if ($pos === 0) {
            $word = mb_substr($word, 1);
        }
        return $word;
    }


}