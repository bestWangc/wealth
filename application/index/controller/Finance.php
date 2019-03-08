<?php
namespace app\index\controller;

use think\Db;
use think\facade\Request;

class Finance extends Base
{
    public function index()
    {
        return $this->fetch();
    }

    //财务记录
    public function recordList(Request $request)
    {
        $listValue = $request::post('listValue',3);

        switch ($listValue) {
            case 1: //收入
                $where = [
                    ['uid','=',$this::$uid],
                    ['epoints', '>=', 0]
                ];
                break;
            case 2: //支出
                $where = [
                    ['uid','=',$this::$uid],
                    ['epoints', '<', 0]
                ];
                break;
            default: //全部
                $where = [
                    "uid" => $this::$uid,
                ];
                break;
        }

        $list = Db::name("money_history")
            ->where($where)
            ->order("id desc")
            ->select();
        if(!empty($list)){
            foreach ($list as $key => &$value){
                $value['create_time'] = date('Y-m-d H:i',$value['create_time']);
            }
        }

        return jsonRes(0,'success',$list);
    }

    public function buyWorker(Request $request)
    {
        $userInfo = $this::$userInfo;
        $coinPrice = getConfig('coin_price');

        $type = $request::param('type',0);
        $this->assign([
            'money' => $userInfo['money'],
            'coinPrice' => $coinPrice,
            'type' => $type
        ]);
        return $this->fetch();
    }

    public function doBuyWorker(Request $request)
    {
        $num = $request::post('buy_num/d');
        if(empty($num)){
            return jsonRes(1,'请输入购买数量');
        }

        $coinPrice = (int)getConfig('coin_price');
        $userInfo = $this::$userInfo;
        $amount = (int)$userInfo['money'];
        $price = $num * $coinPrice;
        if($price > $amount){
            return jsonRes(1,'钱包余额不足！请充值');
        }
        Db::startTrans();
        try {

            $data = [];
            for ($i = 0; $i < $num; $i++){
                $temp = [
                    'user_id' => $this::$uid,
                    'money' => 0,
                    'status' => 1,
                    'create_time' => time()
                ];
                array_push($data,$temp);
            }
            Db::name('user')->insertAll($data);

            // throw new Exception('收益币错误，请重试');
            if(!$res) throw new Exception('收益币错误，请重试');
            $writeMoney = writeMoney($this::$uid,$price, "购买矿工", 0);
            if(!$writeMoney) throw new Exception('money错误，请重试');
            // 提交事务
            Db::commit();
            return jsonRes(0,'购买收益币成功');
        } catch (\Exception $e) {
            // 回滚事务
            Db::rollback();
            return jsonRes(0,$e->getMessage());
        }
    }
}
