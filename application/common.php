<?php
use think\Db;
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
 * 写入用户财务记录
 * @param $uid
 * @param $money 金额
 * @param string $text 说明
 * @param int $type 类型 0-其他 1-利息 2-提现 3-充值 4-奖励 5-签到 6-返还
 * @return bool
 * @throws \think\db\exception\DataNotFoundException
 * @throws \think\db\exception\ModelNotFoundException
 * @throws \think\exception\DbException
 */
function writeMoney($uid, $money, $text = "", $type = 0) {
    $user_info = Db::name('users')->where("id", $uid)->find();
    if ($user_info) {
        $old_money = $user_info['money'];
        if($type == 1 || $type == 4 || $type = 5 || $type = 6){
            $new_money = $old_money + $money;
        }else{
            $new_money = $old_money - $money;
        }

        Db::startTrans();
        try {
            if($type == 0 || $type == 2){
                $res = Db::name('users')
                    ->where('id',$uid)
                    ->setDec('money',$money);
            }else{
                $res = Db::name('users')
                    ->where('id',$uid)
                    ->setInc('money',$money);
            }

            if(!$res) throw new Exception('余额错误');

            $data = [
                "uid" => $uid,
                "action_type" => $type,
                "create_time" => time(),
                "epoints" => $money,
                "original" => $old_money,
                "after" => $new_money,
                "text" => $text
            ];
            $res = Db::name('money_history')->insert($data);

            if(!$res) throw new Exception('新建记录错误');
            // 提交事务
            Db::commit();
            return true;
        } catch (\Exception $e) {
            // 回滚事务
            Db::rollback();
            return false;
        }
    }
}

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
 * @param $time
 * @return false|string
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
 * 管理员登录状态检测
 */
function check_admin(){
    if(session('ly_id')==''||session('ly_name')==''){
        pop('您尚未登录系统，请正确登录系统！',url("index/index"));
        exit;
    }
}

//上传图片
function uploadPic($file,$name)
{
    $fileName = '/uploads/alipay/';
    $name = md5($name);
    $info = $file->validate(['ext'=>'jpg,png'])->move('./uploads/alipay',$name);
    if($info){
        $fileName .= $info->getSaveName();
        return $fileName;
    }
    return '';
}

//获取配置内容
function getConfig($name)
{
    $res = db('config')
        ->where('name',$name)
        ->value('val');
    return $res;
}