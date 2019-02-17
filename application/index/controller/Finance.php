<?php
namespace app\index\controller;

use think\Db;
use think\facade\Request;
use ORG\Util\Page;
/**
 * 财务中心
 * Class Finance
 * @package app\index
 */
class Finance extends Base
{

    public function index(Request $request)
    {
        $id = $request::param('id/d');
        $page = $request::param('page',1);

        switch ($id) {
            case 1: //收入
                $where = [
                    ['uid','=',$this::$user_id],
                    ['epoints', '>=', 0]
                ];
                break;
            case 2: //支出
                $where = [
                    ['uid','=',$this::$user_id],
                    ['epoints', '<', 0]
                ];
                break;
            default: //全部
                $where = [
                    "uid" => $this::$user_id,
                ];
                break;
        }

        $list = Db::name("money_history")
                ->where($where)
                ->order("id desc")
                ->paginate(15,false,[
                    'page' => $page
                ]);

        $page = $list->render();
        $this->assign([
            'nav' => 4,
            'btn' => $id,
            'page' => $page,
            'list' => $list
        ]);
        return $this->fetch();
    }

    public function buy()
    {
        $this->assign('nav', 7);
        return $this->fetch();
    }

    /**
     * 购买收益币
     * @param Request $request
     * @return \think\response\Json
     */
    public function doCoinBuy(Request $request)
    {
        $num = intval($request::post('coin_num/d'));

        if ($num <= 0) {
            return jsonRes(0,"购买数量错误");
        }

        $userInfo = $this::$userInfo;
        $price = $num * $this::$coinPrice;
        if ($price > $userInfo['money']) {
            return jsonRes(0,"钱包余额不足！请充值");
        }

        Db::startTrans();
        try {
            $res = Db::name("users")
                ->where("id", $this::$user_id)
                ->setInc('coin',$num);

            // throw new Exception('收益币错误，请重试');
            if(!$res) throw new Exception('收益币错误，请重试');
            $writeMoney = $this->writeMoney($this::$user_id,$price, "购买收益币", 0);
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

    public function cashing()
    {
        $list = Db::name("extract_apply")
            ->where(array("uid" => $this::$user_id))
            ->order("id desc")
            ->limit(10)
            ->select();
        if(!empty($list)){
            foreach ($list as $key => &$value){
                $value['create_time'] = date('Y-m-d H:i',$value['create_time']);
                if($value['update_time'] != 0){
                    $value['update_time'] = date('Y-m-d H:i',$value['update_time']);
                }
            }
        }

        $this->assign([
            'nav' => 6,
            'list' => $list
        ]);

        return $this->fetch();
    }

    /**
     * 提现申请
     */
    public function doCashing(Request $request)
    {
        $epoints = intval($request::post('epoints/d'));
        $remark = $request::post('remark','');

        $money = [50, 100, 200, 500, 1000, 3000];

        if (!in_array($epoints, $money)) {
            return jsonRes(1,"提现金额错误");
        }

        $minCash = MC("cash_min");
        if ($epoints < $minCash) {
            return jsonRes(1,"最小提现金额为{$minCash}元");
        }

        //计算手续费并进行四舍五入
        $cashFea = MC("cash_fee");
        $fee = round($epoints * $cashFea,2);

        $userInfo = $this::$userInfo;
        if (($epoints + $fee) > $userInfo['money']) {
            return jsonRes(1,'钱包余额不足');
        }

        $count = Db::name("extract_apply")
            ->where("uid", $this::$user_id)
            ->where("create_date",get_date())
            ->count();

        if ($count >= MC('cash_num')) {
            return jsonRes(1,"今日提现次数已用完");
        }

        Db::startTrans();
        try {
            $data = [
                "uid" => $this::$user_id,
                "epoints" => $epoints,
                "remark" => $remark,
                "create_time" => time(),
                "create_date" => get_date(),
                "status" => 0
            ];

            $res = Db::name("extract_apply")->insert($data);

            if(!$res) throw new Exception('提交错误，请重试');

            $writeMoney = $this->writeMoney($this::$user_id,$epoints, "提现", 2);
            if(!$writeMoney) throw new Exception('提现错误，请重试');

            if($fee>0){
                $writeMoney = $this->writeMoney($this::$user_id, $fee, '提现手续费' . $cashFea, 2);
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
}
