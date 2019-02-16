<?php
namespace app\index\controller;

use think\Db;
use think\facade\Request;

/**
 * 用户中心
 */
class Recharge extends Base
{
    public function index()
    {
        $list = Db::name("ZhifubaoChongzhi")
            ->where(array("uid" => $this::$user_id))
            ->order("id desc")->limit(10)
            ->select();

        $this->assign([
            'nav' => 5,
            'list' => $list
        ]);
        return $this->fetch();
    }
}
