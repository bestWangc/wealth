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
        $workerTypeInfo = Worker::getWorkerTypeInfo();

        $type = $request::param('type',0);
        $this->assign([
            'money' => $userInfo['money'],
            'workerTypeInfo' => $workerTypeInfo,
            'type' => $type
        ]);
        return $this->fetch();
    }

    public function doBuyWorker(Request $request)
    {
        $num = $request::post('buy_num/d');
        $workerTypeId = $request::post('worker_type/d');
        if(empty($num)) return jsonRes(1,'请输入购买数量');
        if(empty($workerTypeId)) return jsonRes(1,'请选择购买矿工种类');

        $workerTypeInfo = Worker::getWorkerTypeInfo($workerTypeId);
        $workerTypeInfo = $workerTypeInfo[0];

        $userInfo = $this::$userInfo;
        $amount = (int)$userInfo['money'];
        $price = $num * $workerTypeInfo['price'];
        if($price > $amount){
            return jsonRes(1,'钱包余额不足！请充值');
        }
        if($workerTypeInfo['retire'] > 0){
            $worker = Db::name('worker')
                ->where('user_id',$this::$uid)
                ->where('worker_type_id',$workerTypeInfo['id'])
                ->count('id');
            if($worker > 0){
                return jsonRes(1,'普通矿工每个账号只能购买一次');
            }
        }
        Db::startTrans();
        try {
            $data = [];
            for ($i = 0; $i < $num; $i++){
                $temp = [
                    'user_id' => $this::$uid,
                    'worker_type_id' => $workerTypeInfo['id'],
                    'status' => 1,
                    'create_time' => time()
                ];
                array_push($data,$temp);
            }
            $res = Db::name('worker')->insertAll($data);

            if($res != $num) throw new Exception('矿工错误，请重试');
            $writeMoney = writeMoney($this::$uid,$price, "购买矿工", 0);
            if(!$writeMoney) throw new Exception('money错误，请重试');
            Db::name('users')
                ->where('id',$this::$uid)
                ->setInc('worker',$num);
            // 提交事务
            Db::commit();
            return jsonRes(0,'购买矿工成功');
        } catch (\Exception $e) {
            // 回滚事务
            Db::rollback();
            return jsonRes(0,$e->getMessage());
        }
    }
}
