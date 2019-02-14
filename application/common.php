<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件
/**
 * 获取钱包历史记录类别
 * @param type $id
 */
function get_main_last_status($id) {
    switch ($id) {
        case 1:
            return '利息';
            break;
        case 7:
            return '购买';
            break;
        case 2:
            return '提现';
            break;
        case 3:
            return '充值';
            break;
        case 4:
            return '奖励';
            break;
        case 5:
            return '签到';
            break;
        case 6:
            return '返还';
            break;

        default:
            return '其他';
            break;
    }
}

/**
 * 写入用户财务记录
 * @param type $uid 用户id
 * @param type $money 金额
 * @param string $text 说明
 * @param type $type 类型 0-其他 1-利息 2-提现 3-充值 4-奖励 5-签到 6-返还
 */
function write_money($uid, $money, $text = "", $type = 0) {
    $Users = M("Users");
    $Moneyhistory = M("Moneyhistory");

    $user_info = $Users->where(array("id" => $uid))->find();

    if ($user_info) {
        $old_money = $user_info['money'];
        $new_money = $old_money + $money;

        $user_data = array(
            "money" => $new_money
        );

        $data = array(
            "uid" => $uid,
            "action_type" => $type,
            "create_time" => time(),
            "epoints" => $money,
            "original" => $old_money,
            "after" => $new_money,
            "text" => $text
        );

        $Users->where(array("id" => $uid))->save($user_data);

        $Moneyhistory->add($data);
    }
}

/**
 * 由数据库取出系统的配置
 *
 * @access  public
 * @param   mix     $name
 *
 * @return  mix
 */
function MC($name) {
    //检测session缓存以及是否开启系统配置缓存
    if (session('?config_cache') && C('Cache_config')) {
        $sys_conf = session('config_cache');
        //检测S缓存状态以及是否开启系统配置缓存
    } elseif (S('config_cache') && C('Cache_config')) {
        $sys_conf = S('config_cache');
        session('config_cache', $sys_conf);
    } else {
        $sys_conf = M("Config")->where("status=1")->getField("name,val");
        //检测是否开启缓存  开启则进行缓存
        if (C('Cache_config')) {
            S('config_cache', $sys_conf, C('Cache_config_time'));
            session('config_cache', $sys_conf);
        }
    }
    return $sys_conf[$name];
}

/**
 * 传递级别ID获取级别信息 其他级别获取数组
 */
function get_jibie($id) {
    if ($id == 1) {
        return "普通会员";
    }
    $info = M("Jibie")->where(array("id" => $id))->find();
    if (empty($info)) {
        return "普通会员";
    }
    return $info;
}

//人性化时间戳
function tranTime($time) {
    $rtime = date("Y-m-d H:i", $time);
    $htime = date("H:i", $time);
    $time = time() - $time;
    if ($time < 60) {
        $str = '刚刚';
    } elseif ($time < 60 * 60) {
        $min = floor($time / 60);
        $str = $min . '分钟前';
    } elseif ($time < 60 * 60 * 24) {
        $h = floor($time / (60 * 60));
        $str = $h . '小时前 ' . $htime;
    } elseif ($time < 60 * 60 * 24 * 3) {
        $d = floor($time / (60 * 60 * 24));
        if ($d == 1)
            $str = '昨天 ' . $rtime;
        else
            $str = '前天 ' . $rtime;
    }else {
        $str = $rtime;
    }
    return $str;
}

/**
 * 判断是否为时间
 * @var String $str 日期  Y-m-d
 */
function isDate($str, $format = "Y-m-d") {
    $unixTime = strtotime($str);
    $checkDate = date($format, $unixTime);
    if ($checkDate == $str)
        return true;
    else
        return false;
}

/**
 * 传递日期，获取月份
 * @var String $str 日期  Y-m-d
 */
function get_month($str) {
    list($year, $month, $day) = split("-", $str);
    return $month;
}

/**
 * 传递秒数 获取Y-m-d H:i:s格式的时间
 * @var Int $time 距离1970的秒数
 */
function get_date_full($time) {
    $time = $time? : time();
    return date('Y-m-d H:i:s', $time);
}

/**
 * 传递秒数 获取Y-m-d格式的时间
 * @var Int $time 距离1970的秒数
 */
function get_date($time) {
    $time = $time? : time();
    return date('Y-m-d', $time);
}

/**
 * url 301跳转函数
 * @param String $url 完整的url地址
 */
function redirect_301($url = '') {
    header("HTTP/1.1 301 Moved Permanently");
    header("Location: {$url}");
    exit();
}

function rmdirr($dirname) {
    if (!file_exists($dirname)) {
        return false;
    }
    if (is_file($dirname) || is_link($dirname)) {
        return unlink($dirname);
    }
    $dir = dir($dirname);
    while (false !== $entry = $dir->read()) {
        if ($entry == '.' || $entry == '..') {
            continue;
        }
        rmdirr($dirname . DIRECTORY_SEPARATOR . $entry);
    }
    $dir->close();
    return rmdir($dirname);
}