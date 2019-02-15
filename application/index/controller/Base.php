<?php
namespace app\index\controller;

use think\Controller;
use think\facade\Session;
use think\Db;

class Base extends Controller
{

    protected static $user_id;

    protected function initialize()
    {
        parent::initialize();
        $this->checkLogin();

        $user_id = Session::get('u_id');
        $this::$user_id = $user_id;
        $user_info = Db::name("Users")->where(array("id" => $user_id))->find();
        $jibie = get_jibie($user_info['jibie_id']);
        $user_info['jibie_name'] = is_array($jibie) ? $jibie['title'] : $jibie;

        $this->user_info = $user_info;
    }

    public function checkLogin(){
        //seeion没有u_id 重新登录
        if(!session('u_id')) $this->redirect('/index/login');
    }

}
