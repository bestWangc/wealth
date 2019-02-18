<?php
namespace app\index\controller;

use think\Db;

class MoneyHistry extends Base
{
    public static function getHistoryByLimit($uid,$limit)
    {
        $res = Db::name("money_history")
            ->where('uid',$uid)
            ->order("id desc")
            ->limit($limit)
            ->select();
        return $res;
    }
}
