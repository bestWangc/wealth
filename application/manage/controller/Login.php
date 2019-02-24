<?php

namespace app\manage\controller;

use think\Controller;
use think\facade\Request;
use think\Db;
use think\facade\Session;

class Login extends Controller
{
    public function index()
    {
        return $this->fetch();
    }

    public function doLogin(Request $request)
    {
        $userName = $request::post('username');
        $userPwd = $request::post('userpwd');

        if(empty($userName) || empty($userPwd)){
            return jsonRes(1,'用户名或密码不能为空');
        }
        $userArr = Db::name('admin')
            ->where('name',$userName)
            ->where('status',1)
            ->field('id,name,password')
            ->find();

        if(empty($userArr)){
            return jsonRes(1,'用户名不存在或被禁用！请重新登录');
        }

        if($userPwd = md5($userPwd.'jstj') == $userArr['password']){
            //将user id 存入session中
            Session::set('uid',$userArr['id']);
            Session::set('uname',$userArr['name']);

            return jsonRes(0,'登录成功');
        }else{
            return jsonRes(1,'密码错误');
        }
    }
}
