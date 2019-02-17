<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018-8-11
 * Time: 10:48
 */

namespace app\service\controller;

use app\tools\M3result;
use think\facade\Request;
use think\Db;

class Register extends Base
{
    public function index(Request $request)
    {
        $tuijian_switch = MC("tuijian_switch");

        $parent_id = $request::param('parent_id');

        if(empty($parent_id)){
            return jsonRes(1,'推荐人不正确或不存在');
        }
        $parentInfo = Db::name("users")
            ->where('status',1)
            ->where('id',$parent_id)
            ->find();
        if ($tuijian_switch) {
            if (empty($parentInfo)) {
                return jsonRes(1,'推荐人不正确或不存在');
            }
        }

        $userName = $request::param('username', "");
        $userPwd = $request::param('userpwd', "");
        $secondPwd = $request::param('secondPwd', "");
        $phone = $request::param('phone', "");

        if(empty($userName) || empty($userPwd) || empty($secondPwd)){
            return jsonRes(1,'信息填写不全，请重试');
        }
        if(empty($phone)){
            return jsonRes(1,'手机号不能为空');
        }
        $pattern = '/^1[34578]\d{9}$/';
        if(!preg_match($pattern,$phone)){
            return jsonRes(1,'请填写正确的手机号');
        }

        $oldUserInfo = Db::name('users')
            ->where('user_name',$userName)
            ->count('id');

        if(!!$oldUserInfo){
            return jsonRes(1,'帐号已存在，请重新填写');
        }

        $path = empty($parentInfo['id']) ? "" : "{$parentInfo['id']}";
        if (!empty($parent_id) && !empty($parentInfo['path'])) {
            $path .= ',' . $parentInfo['path'];
        }

        $data = [
            'user_name' => $userName,
            'user_pwd' => md5($userPwd.'jstj'),
            'user_pwd1' => md5($secondPwd.'jstj'),
            'mobile' => $phone,
            "main" => $parentInfo['id'],
            "path" => $path,
            "last_login_time" => time(),
            "update_time" => time(),
            'create_time' => time(),
            "create_ip" => get_client_ip(),
            "login_count" => 1,
            "last_login_ip" => get_client_ip()
        ];

        $creatUser = Db::name('users')->insert($data);

        if($creatUser) return jsonRes(0,'注册成功');

        return jsonRes(1,'注册失败，请重试');
    }
}