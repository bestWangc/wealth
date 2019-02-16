<?php
namespace app\index\controller;

use think\Controller;
use think\facade\Session;
use think\Db;

class Base extends Controller
{

    protected static $user_id;

    //网站配置
    public static $siteName;
    public static $signIncome;

    protected function initialize()
    {
        parent::initialize();
        $this->checkLogin();

        $user_id = Session::get('u_id');

        self::$user_id = $user_id;
        $siteName = MC('site_name');
        $signIncome = MC('sign_income');

        $userInfo = Db::name("users")
            ->where("id", $user_id)
            ->find();
        $jibie = get_jibie($userInfo['jibie_id']);
        $userInfo['jibie_name'] = is_array($jibie) ? $jibie['title'] : $jibie;
        $this->assign([
            'user_info' => $userInfo,
            'siteName' => $siteName,
            'signIncome' => $signIncome
        ]);
    }

    public function checkLogin(){
        //seeion没有u_id 重新登录
        if(!session('u_id')) $this->redirect('/index/login');
    }

}
