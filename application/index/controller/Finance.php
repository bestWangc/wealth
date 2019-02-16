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
            var_dump($e->getMessage());
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
        $this->nav = 6;

        $this->list = Db::name("TixianApply")
            ->where(array("uid" => $this::$user_id))
            ->order("id desc")
            ->limit(10)
            ->select();

        return $this->fetch();
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

    /**
     * 提现申请
     */
    public function cash_action()
    {
        $epoints = intval($_POST['epoints']);

        $money = [50, 100, 200, 500, 1000,3000];

        if (!in_array($epoints, $money)) {
            $this->error("提现金额错误！");
        }

        if ($epoints < MC("cash_min")) {
            $this->error("提现金额必须大于最小提现金额！");
        }

        $fee = round($epoints * MC("cash_fee"),2); //计算手续费并进行四舍五入

        if (($epoints + $fee) > $this->user_info['money']) {
            $this->error("钱包余额不足！");
        }

        $count = Db::name("TixianApply")
            ->where(["uid" => $this::$user_id, "create_date" => get_date()])
            ->count();

        if ($count >= MC('cash_num')) {
            $this->error("超过每日提现限制！");
        }

        $data = array(
            "uid" => $this::$user_id,
            "epoints" => $epoints,
            "create_time" => time(),
            "create_date" => get_date(),
            "status" => 0
        );

        Db::name("TixianApply")->add($data);

        write_money($this::$user_id, -$epoints, "提现", 2);
        if($fee>0){
            write_money($this::$user_id, -$fee, "提现手续费" . MC('cash_fee'), 2);
        }

        $this->success("提现申请提交成功！", url("finance/cashing"));
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
