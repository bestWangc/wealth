<?php
namespace app\index\controller;

use think\Db;
use think\facade\Request;
use app\service\controller\Recharge as sRecharge;

/**
 * 用户中心
 */
class Recharge extends Base
{
    public function index()
    {
        $list = Db::name("recharge")
            ->where("user_id", $this::$user_id)
            ->order("id desc")
            ->limit(10)
            ->select();

        $this->assign([
            'nav' => 5,
            'list' => $list
        ]);
        return $this->fetch();
    }

    //充值
    public function createRecharge(Request $request)
    {
        $rMoney = $request::post('recharge_money/d',0);
        $rWay = $request::post('recharge_way/d');

        if($rMoney < $this::$coinPrice){
            return jsonRes(1,'最少充值{$this::$coinPrice}元');
        }

        $res = sRecharge::createRecharge($this::$user_id,$rMoney,$rWay);
        return $res;
    }
}
