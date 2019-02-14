<?php
namespace app\service\controller;

use think\Db;
use think\Exception;
use think\facade\Request;

class Goods extends Base
{
    /**
     * 购买商品
     * @param Request $request
     * @return \think\response\Json
     */
    public function buyGoods(Request $request)
    {
        $type = $request::post('type');

        $uid = $this->getUid($type);
        if(empty($uid)){
            return jsonRes(3,'请先登录再购买');
        }

        $choose = $request::post('award_num');
        $buy_num = $request::post('buy_num',0);
        $goods_id = $request::post('goods_id',0);

        if(empty($buy_num) || empty($goods_id) || is_null($choose)){
            return jsonRes(1,'请选择购买数量或者选择升级选项');
        }
        /*$hours = (int)date('H',time());
        if($hours >= 22 || $hours < 10){
            return jsonRes(1,'抢购时间为10点-22点，请稍候');
        }*/

        Db::startTrans();
        try {
            $award_id = Db::name('award_info')
                ->whereNull('result')
                ->order('id desc')
                ->value('id');

            $goodsPrice = Db::name('goods')
                ->where('id',$goods_id)
                ->value('price');

            $amount = $buy_num*$goodsPrice;
            $userMoney = Db::name('users')
                ->where('id',$uid)
                ->field('money,frozen_money,money-frozen_money as dprice')
                ->find();

            if($userMoney['dprice'] < $amount){
                throw new Exception('余额不足，请充值');
            }

            unset($userMoney);
            $data = [
                'user_id' => $uid,
                'goods_id' => $goods_id,
                'goods_num' => $buy_num,
                'amount' => $amount,
                'created_date' => time(),
                'guessing' => $choose,
                'award_id' => $award_id,
                'status' => 0
            ];
            $res = Db::name('order')->insert($data);
            if($res){
                Db::name('users')
                    ->where('id',$uid)
                    ->setDec('money',$amount);
            }
            // 提交事务
            Db::commit();
            return jsonRes(0,'抢购成功');
        } catch (\Exception $e) {
            // 回滚事务
            Db::rollback();
            return jsonRes(1,$e->getMessage());
        }
        return jsonRes(1,'失败，请重试');
    }
}