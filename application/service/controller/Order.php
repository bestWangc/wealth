<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018-8-11
 * Time: 10:48
 */

namespace app\service\controller;

use think\Db;
use think\facade\Request;
use think\facade\Session;

class Order extends Base
{
    //转为金币
    public function toGold(Request $request)
    {
        $type = $request::post('type');

        $uid = $this->getUid($type);

        if(empty($uid)){
            return jsonRes(1,'错误，请重试');
        }

        $order_id = $request::post('order_id','');
        if(empty($order_id)){
            return jsonRes(1,'订单编号不能为空');
        }

        $orderInfo = Db::name('order o')
            ->join('award_info ai','ai.id = o.award_id','left')
            ->field('o.amount,o.guessing,ai.win')
            ->where('o.id',$order_id)
            ->where('status',0)
            ->find();

        $gold = (int)$orderInfo['amount'];
        $gold = (int)($gold/10);

        Db::startTrans();
        try {
            $res = Db::name('users')
                ->where('id',$uid)
                ->lock()
                ->setInc('gold',$gold);
            if($res){
                Db::name('order')
                    ->where('id',$order_id)
                    ->setField('status',5);
            }
            // 提交事务
            Db::commit();
            return jsonRes(0,'成功');
        } catch (\Exception $e) {
            // 回滚事务
            Db::rollback();
        }

        return jsonRes(1,'失败');
    }

    //提货、退货
    public function takeGoods(Request $request)
    {
        $type = $request::post('type');
        $uid = $this->getUid($type);

        if(empty($uid)){
            return jsonRes(1,'错误，请重试');
        }
        $order_id = $request::post('order_id','');
        $purpose = $request::post('purpose',0);
        if(empty($order_id)){
            return jsonRes(1,'订单编号不能为空');
        }
        $applyOrderNum = Db::name('apply')
            ->where('order_id',$order_id)
            ->count();
        if($applyOrderNum > 0){
            return jsonRes(1,'订单已提交，请勿重复操作');
        }
        $addressInfo = Db::name('address')
            ->where('user_id',$uid)
            ->field('id')
            ->count();
        if(!$addressInfo){
            return jsonRes(1,'未设置收货地址');
        }

        if ($purpose == 2){
            //查询余额是否够10元
            $balance = Db::name('users')
                ->where('id',$uid)
                ->value('money');
            if((int)$balance < 10){
                return jsonRes(1,'金额不足10元，无法支付运费');
            }
        }

        $data=[
            'order_id' => $order_id,
            'user_id' => $uid,
            'created_date' => time(),
            'purpose' => $purpose,
            'status' => 2
        ];

        Db::startTrans();
        try {
            $res = Db::name('apply')->insert($data);

            if($res){
                Db::name('order')
                    ->where('id',$order_id)
                    ->setField('status',$purpose);
            }
            // 提交事务
            Db::commit();
            return jsonRes(0,'提交成功');
        } catch (\Exception $e) {
            // 回滚事务
            Db::rollback();
        }
        return jsonRes(1,'未知错误，请重试');
    }

}