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
            return jsonRes(1,'用户名或者密码不能为空');
        }
        $userArr = Db::name('users')
            ->where('name',$userName)
            ->whereOr('email',$userName)
            ->field('id,name,passwd,role,status')
            ->find();

        if(empty($userArr['id'])){
            return jsonRes(1,'用户名不存在');
        }
        if(!$userArr['status']){
            return jsonRes(1,'用户未激活');
        }
        if($userPwd = md5($userPwd.'jfn') == $userArr['passwd']){
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
            Session::set('user_role',$userArr['role']);
            Session::set('user_name',$userArr['name']);

            return jsonRes(0,'登录成功');
        }else{
            return jsonRes(1,'登录失败');
        }
    }
}