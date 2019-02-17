<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018-8-5
 * Time: 11:00
 */

namespace app\manage\controller;

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
        if(!session('uid')) $this->redirect('/manage/login');
    }

    //禁止翻墙登录
    public function checkRole(){
        //seeion没有user_id 重新登录
        // if(!session('user_role')) abort(403,'权限不够');
        if(is_null(session('user_role'))) abort(403,'权限不够');
    }

}