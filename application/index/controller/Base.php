<?php
namespace app\index\controller;

use think\Controller;

class Base extends Controller
{
    protected $uid;
    protected function initialize()
    {
        parent::initialize();
        $this->checkLogin();
        $this->checkRole();
        $this->uid = session('uid');
    }

    public function checkLogin(){
        //seeion没有user_id 重新登录
        if(!session('u_id')) $this->redirect('/index/login');
    }

    //禁止翻墙登录
    public function checkRole(){
        //seeion没有user_id 重新登录
        if(is_null(session('user_role'))) abort(403,'权限不够');
    }

}