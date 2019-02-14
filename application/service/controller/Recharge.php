<?php
namespace app\service\controller;

use think\Db;
use think\Exception;
use shpay\Shpay;

class Recharge extends Base
{
    public static function createRecharge($uid,$rMoney,$rWay)
    {
        if($rMoney == 0){
            return jsonRes(1,'请输入正确的金额');
        }
        if(is_null($rWay)){
            return jsonRes(1,'请选择充值方式');
        }
        //流水编号
        $orderNum = date('Ymd').substr(implode(NULL, array_map('ord', str_split(substr(uniqid(), 7, 13), 1))), 0, 8);
        $data = [
            'user_id' => $uid,
            'amount' => $rMoney,
            'order_no' => $orderNum,
            'status' => 2,
            'created_date' => time(),
            'way' => $rWay
        ];

        Db::startTrans();
        try {
            $result = Db::name('recharge')
                ->insert($data);
            if($result){
                $shpay = new Shpay();
                if($rWay){
                    $way = 'ALP'; //支付宝
                }else{
                    $way = 'WXP'; //微信
                }

                $resUrl = $shpay->createOrder($orderNum,$rMoney,$way);
                if(!empty($resUrl)){
                    $resUrl = json_decode($resUrl);
                    if(property_exists($resUrl,'qrcode') && !empty($resUrl->qrcode)){
                        $result = ['url' => $resUrl->qrcode,'way' => $rWay];
                    }
                }

                if(empty($result)){
                    throw new Exception('充值失败,请重试');
                }
            }else{
                throw new Exception('充值记录生成失败');
            }
            // 提交事务
            Db::commit();
            return jsonRes(0,'success',$result);
        } catch (\Exception $e) {
            // 回滚事务
            Db::rollback();
            return jsonRes(1,$e->getMessage());
        }

    }
}