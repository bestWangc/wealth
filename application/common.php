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
 * josn 返回函数
 * @param int $code
 * @param $msg
 * @param array $data
 * @return \think\response\Json
 */
function jsonRes($code, $msg, $data = []){
    $data = [
        'code' => $code,
        'msg' => $msg,
        'data' => $data
    ];
    return json($data);
}

/**
 * 由数据库取出系统的配置
 *
 * @access  public
 * @param   mix
 * @return  mix
 */
function MC($name) {
    //检测session缓存以及是否开启系统配置缓存
    if (session('?config_cache') && config('Cache_config')) {
        $sys_conf = session('config_cache');
        //检测S缓存状态以及是否开启系统配置缓存
    } elseif (cache('config_cache') && config('Cache_config')) {
        $sys_conf = cache('config_cache');
        session('config_cache', $sys_conf);
    } else {
        $sys_conf = db("config")
            ->where('status',1)
            ->field("name,val")
            ->select();
        $sys_conf_new = [];
        foreach ($sys_conf as $key => $value){
            $sys_conf_new[$value['name']] = $value['val'];
        }

        //检测是否开启缓存  开启则进行缓存
        if (config('Cache_config')) {
            cache('config_cache', $sys_conf_new, config('Cache_config_time'));
            session('config_cache', $sys_conf_new);
        }
    }
    return $sys_conf_new[$name];
}

/**
 * 传递级别ID获取级别信息 其他级别获取数组
 * @param $id
 * @return array|PDOStatement|string|\think\Model|null
 * @throws \think\db\exception\DataNotFoundException
 * @throws \think\db\exception\ModelNotFoundException
 * @throws \think\exception\DbException
 */
function getLevelById($id) {
    if ($id == 1) {
        return "普通会员";
    }
    $info = db("level")
        ->where("id", $id)
        ->find();
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
 * @param $time
 * @return false|string
 */
function get_date($time = '') {
    $time = $time ? : time();
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

// 获取客户端IP地址
function get_client_ip() {
    static $ip = NULL;
    if ($ip !== NULL) return $ip;
    if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $arr = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
        $pos =  array_search('unknown',$arr);
        if(false !== $pos) unset($arr[$pos]);
        $ip   =  trim($arr[0]);
    }elseif (isset($_SERVER['HTTP_CLIENT_IP'])) {
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    }elseif (isset($_SERVER['REMOTE_ADDR'])) {
        $ip = $_SERVER['REMOTE_ADDR'];
    }
    // IP地址合法验证
    $ip = (false !== ip2long($ip)) ? $ip : '0.0.0.0';
    return $ip;
}


/**
 * 弹出提示框
 *
 * @var String $name 提示信息
 * @var String $url 返回链接
 *
 */
function pop($name,$url){
    header("Content-Type: text/html; charset=UTF-8");
    echo '<script language="javascript">';
    echo "alert('{$name}');";
    echo "location.href='{$url}'";
    echo '</script>';
    exit();
}


/**
 * 弹出提示框，并执行一个脚本
 *
 * @var String $name 提示信息
 * @var String 其他脚本
 *
 */
function pop_js($name,$js){
    header("Content-Type: text/html; charset=UTF-8");
    echo '<script language="javascript">';
    echo "alert('{$name}');";
    echo "{$js}";
    echo '</script>';
    exit();
}

/**
 * 弹出提示框，上级框架跳转
 *
 * @var String $name 提示信息
 * @var String $url 返回链接
 *
 */
function pop_parent($name,$url){
    header("Content-Type: text/html; charset=UTF-8");
    echo '<script language="javascript">';
    echo "alert('{$name}');";
    echo "parent.location.href='{$url}'";
    echo '</script>';
    exit();
}


/**
 * 管理员登录状态检测
 */
function check_admin(){
    if(session('ly_id')==''||session('ly_name')==''){
        pop('您尚未登录系统，请正确登录系统！',url("index/index"));
        exit;
    }
}