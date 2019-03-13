<?php
namespace app\index\controller;

use think\Db;
use think\facade\Request;

class Extract extends Base
{

    public function index()
    {
        $list = $this->getRecord($this::$uid);

        $userInfo = $this::$userInfo;
        $limit = getConfig('cash_num');
        $moneyMin = getConfig('cash_min');
        $fee = getConfig('cash_fee');
        $this->assign([
            'money' => $userInfo['money'],
            'limit' => $limit,
            'moneyMin' => $moneyMin,
            'fee' => $fee,
            'list' => json_encode($list)
        ]);

        return $this->fetch();
    }

    /**
     * 提现申请
     */
    public function doCashing(Request $request)
    {
        $epoints = intval($request::post('epoints/d'));

        $money = [50, 100, 200, 500, 1000, 3000];

        if (!in_array($epoints, $money)) {
            return jsonRes(1,"提现金额错误");
        }

        $minCash = getConfig('cash_min');
        if ($epoints < $minCash) {
            return jsonRes(1,"最小提现金额为{$minCash}元");
        }

        //计算手续费并进行四舍五入
        $cashFea = getConfig('cash_fee');
        $fee = round($epoints * $cashFea,2);

        $userInfo = $this::$userInfo;
        if (($epoints + $fee) > $userInfo['money']) {
            return jsonRes(1,'钱包余额不足');
        }

        $count = Db::name("extract_apply")
            ->where("uid", $this::$uid)
            ->where("create_date",get_date())
            ->count();

        $cashNum = (int)getConfig('cash_num');
        if ($count >= $cashNum) {
            return jsonRes(1,"今日提现次数已用完");
        }

        Db::startTrans();
        try {
            $data = [
                "uid" => $this::$uid,
                "epoints" => $epoints,
                "create_time" => time(),
                "create_date" => get_date(),
                "status" => 2
            ];

            $res = Db::name("extract_apply")->insert($data);

            if(!$res) throw new Exception('提交错误，请重试');

            $writeMoney = writeMoney($this::$uid,$epoints, "提现", 2);
            if(!$writeMoney) throw new Exception('提现错误，请重试');

            if($fee > 0){
                $writeMoney = writeMoney($this::$uid, $fee, '提现手续费' . $cashFea, 2);
                if(!$writeMoney) throw new Exception('提现手续费记录错误，请重试');
            }
            // 提交事务
            Db::commit();
            return jsonRes(0,'提现申请提交成功');
        } catch (\Exception $e) {
            // 回滚事务
            Db::rollback();
            return jsonRes(0,$e->getMessage());
        }
    }

    //提现记录
    public function getRecord($uid)
    {
        $list = Db::name("extract_apply")
            ->where("uid", $this::$uid)
            ->order("id desc")
            ->select();
        if(!empty($list)){
            foreach ($list as $key => &$value){
                $value['create_time'] = date('Y-m-d H:i',$value['create_time']);
                if($value['update_time'] != 0){
                    $value['update_time'] = date('Y-m-d H:i',$value['update_time']);
                }
            }
        }
        return $list;
    }
}
