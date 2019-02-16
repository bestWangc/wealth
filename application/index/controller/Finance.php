<?php
namespace app\index\controller;

use think\Db;
use think\facade\Request;

/**
 * 财务中心
 * Class Finance
 * @package app\index
 */
class Finance extends Base
{

    public function index(Request $request)
    {
        $id = intval($request::param('id'));
        
        switch ($id) {
            case 1: //收入
                $where = [
                    "uid" => $this::$user_id,
                    "epoints" => array("egt", 0)
                ];
                break;
            case 2: //支出
                $where = [
                    "uid" => $this::$user_id,
                    "epoints" => array("lt", 0)
                ];
                break;
            default: //全部
                $where = [
                    "uid" => $this::$user_id,
                ];
                break;
        }

        import('ORG.Util.Page'); // 导入分页类

        $count = Db::name("moneyhistory")
            ->where($where)
            ->count();

        $Page = new Page($count, 15);

        $Page->parameter = "&id={$id}";
        $page = $Page->show();
        $list = Db::name("moneyhistory")
                ->where($where)
                ->order("id desc")
                ->limit($Page->firstRow . ',' . $Page->listRows)
                ->select();

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
                ->setInc('bi',$num);

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

    public function alipay()
    {
        $this->nav = 5;

        $this->list = Db::name("ZhifubaoChongzhi")
            ->where(array("uid" => $this::$user_id))
            ->order("id desc")->limit(10)
            ->select();

        return $this->fetch();
    }

    public function cashing()
    {
        $list = Db::name("tixianApply")
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

        $count = Db::name("tixianApply")
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

            $res = Db::name("tixianApply")->insert($data);

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

    /**
     * 支付宝充值申请操作
     */
    public function alipay_action()
    {
        $epoints = intval($_POST['epoints']);

        if ($epoints < MC("bi_price")) {
            $this->error("充值金额必须大于收益币的价格！");
        }

        if ($_POST['liushui_no'] == '') {
            $this->error("请填写支付宝转账流水号码！");
        }

        $data = array(
            "uid" => $this::$user_id,
            "epoints" => $epoints,
            "create_time" => time(),
            "liushui_no" => $_POST['liushui_no'],
            "text" => $_POST['remark'],
            "status" => 0
        );

        Db::name("ZhifubaoChongzhi")->add($data);

        $this->success("充值申请提交成功！", url("finance/alipay"));
    }

    public function delete_alipay()
    {
        $id = intval($_GET['id']);

        Db::name("ZhifubaoChongzhi")
            ->where(["uid" => $this::$user_id, "id" => $id, "status" => 0])
            ->delete();

        $this->success("删除成功！", url("alipay"));
    }

    public function delete_cash()
    {
        $id = intval($_GET['id']);

        $info = Db::name("TixianApply")
            ->where(["uid" => $this::$user_id, "id" => $id, "status" => 0])
            ->find();

        Db::name("TixianApply")
            ->where(["uid" => $this::$user_id, "id" => $id, "status" => 0])
            ->delete();

        $fee = round($info['epoints'] * MC('cash_fee'), 2);

        if ($info['epoints'] > 0) {
            write_money($this::$user_id, $info['epoints'], "取消提现", 6);
            write_money($this::$user_id, $fee, "取消提现手续费返还", 6);
        }

        $this->success("删除成功！", url("cashing"));
    }

}

function get_action_time($str) {
    if ($str == 0) {
        return "未操作";
    }
    return get_date_full($str);
}

function get_status($status) {
    switch ($status) {
        case 1:
            return "已入账";
            break;
        case 2:
            return "拒绝";
            break;
        default:
            return "未审核";
            break;
    }
}

function get_cash_status($status) {
    switch ($status) {
        case 1:
            return "提现完成";
            break;
        default:
            return "未审核";
            break;
    }
}
