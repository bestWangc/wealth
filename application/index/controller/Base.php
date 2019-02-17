<?php
namespace app\index\controller;

use think\Controller;
use think\Db;

class Base extends Controller
{
    protected static $uid;
    protected static $userInfo;
    protected function initialize()
    {
        parent::initialize();
        $this->checkLogin();
        self::$uid = session('u_id');
        self::$userInfo = $this->getUserInfo(self::$uid);
    }

    public function checkLogin(){
        //seeion没有user_id 重新登录
        if(!session('u_id')) $this->redirect('/index/login');
    }

    public function getUserInfo($uid)
    {
        $res = $userInfo = Db::name("users")
            ->where("id", $uid)
            ->find();
        return $res;
    }
}