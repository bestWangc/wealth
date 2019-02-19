<?php
namespace app\index\controller;

use think\Db;
use think\facade\Request;
use app\service\controller\Recharge as sRecharge;

class Recharge extends Base
{

    public function index()
    {
        $coinPrice = getConfig('coin_price');
        $list = $this->getRecord($this::$uid);

        $this->assign([
            'coinPrice' => $coinPrice,
            'list' => json_encode($list)
        ]);
        return $this->fetch();
    }

    //充值
    public function createRecharge(Request $request)
    {
        $rMoney = $request::post('recharge_money/d',0);
        $rWay = $request::post('recharge_way/d');

        $coinPrice = getConfig('coin_price');
        if($rMoney < $coinPrice){
            return jsonRes(1,'最少充值{$coinPrice}元');
        }

        $res = sRecharge::createRecharge($this::$uid,$rMoney,$rWay);
        return $res;
    }

    //充值记录
    public function getRecord($uid)
    {
        $result = Db::name("recharge")
        ->where("user_id", $uid)
        ->order("id desc")
        ->select();
        if(!empty($result)){
            foreach ($result as $key => &$value){
                $value['created_date'] = date('Y-m-d H:i:s',$value['created_date']);
                if(!empty($value['updated_date'])){
                    $value['updated_date'] = date('Y-m-d H:i:s',$value['updated_date']);
                }
            }
        }
        return $result;
    }
}
