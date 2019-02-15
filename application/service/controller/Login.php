<?php
namespace app\service\controller;

use think\facade\Request;
use think\facade\Session;
use think\Db;

class Login extends Base
{
    public function index(Request $request)
    {
        $userName = $request::param('username', "");
        $userPwd = $request::param('userpwd', "");
        $type = $request::param('type',0);

        if(empty($userName) || empty($userPwd)){
            return jsonRes(1,'用户名或密码不能为空');
        }

        $userArr = Db::name('users')
            ->where('user_name',$userName)
            ->where('status',1)
            ->field('id,user_name,user_pwd,login_count')
            ->find();

        if(empty($userArr)){
            return jsonRes(1,'用户名不存在或被禁用！请重新登录');
        }

        if($userPwd = md5($userPwd.'jstj') == $userArr['user_pwd']){
            //将user id 存入session中
            /*
             * mapp  user_id 1
             * index  u_id 2
             * manage  uid 3
            */
            if($type == 1){
                Session::set('user_id',$userArr['id']);
            } elseif ($type == 2){
                Session::set('u_id',$userArr['id']);
            } elseif ($type == 3){
                Session::set('uid',$userArr['id']);
            }

            Session::set('user_name',$userArr['user_name']);

            $data = [
                "last_login_time" => time(),
                "last_login_ip" => get_client_ip(),
                "login_count" => $userArr['login_count'] + 1,
            ];
            Db::name("users")
                ->where("id", $userArr['id'])
                ->lock()
                ->update($data);

            return jsonRes(0,'登录成功');
        }else{
            return jsonRes(1,'登录失败');
        }
    }
}