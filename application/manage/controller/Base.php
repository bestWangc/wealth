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
        $this->uid = session('uid');
    }

    public function checkLogin(){
        //seeion没有user_id 重新登录
        if(!session('uid')) $this->redirect('/manage/login');
    }
}