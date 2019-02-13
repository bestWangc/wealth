<?php
namespace app\index;
/**
 * 财务中心
 *
 */
class Finance extends Base
{

    public function index() {
        $this->nav = 4;
        $id = intval($_GET['id']);
        $this->btn = $id;
        switch ($id) {
            case 1: //收入
                $where = array(
                    "uid" => $this->user_id,
                    "epoints" => array("egt", 0)
                );
                break;
            case 2: //支出
                $where = array(
                    "uid" => $this->user_id,
                    "epoints" => array("lt", 0)
                );
                break;
            default: //全部
                $where = array(
                    "uid" => $this->user_id,
                );
                break;
        }

        import('ORG.Util.Page'); // 导入分页类


        $count = M("Moneyhistory")->where($where)->count();

        $Page = new Page($count, 15);

        $Page->parameter = "&id={$id}";

        $this->page = $Page->show();

        $this->list = M("Moneyhistory")
                ->where($where)
                ->order("id desc")
                ->limit($Page->firstRow . ',' . $Page->listRows)
                ->select();

        $this->display();
    }

    public function buy() {
        $this->nav = 7;
        $this->display();
    }

    /**
     * 购买收益币
     */
    public function buy_action() {
        $num = intval($_POST['num']);

        if ($num <= 0) {
            $this->error("购买数量错误！");
        }

        $price = $num * MC("bi_price");

        if ($price > $this->user_info['money']) {
            $this->error("钱包余额不足！请充值");
        }

        M("Users")->where(array("id" => $this->user_id))->setField("bi", $this->user_info['bi'] + $num);
        write_money($this->user_id, -$price, "购买收益币", 0);

        $this->success("购买收益币成功！", U("/main"));
    }

    public function alipay() {
        $this->nav = 5;

        $this->list = M("ZhifubaoChongzhi")->where(array("uid" => $this->user_id))->order("id desc")->limit(10)->select();

        $this->display();
    }

    public function cashing() {
        $this->nav = 6;

        $this->list = M("TixianApply")->where(array("uid" => $this->user_id))->order("id desc")->limit(10)->select();

        $this->display();
    }

    /**
     * 支付宝充值申请操作
     */
    public function alipay_action() {
        $epoints = intval($_POST['epoints']);

        if ($epoints < MC("bi_price")) {
            $this->error("充值金额必须大于收益币的价格！");
        }

        if ($_POST['liushui_no'] == '') {
            $this->error("请填写支付宝转账流水号码！");
        }

        $data = array(
            "uid" => $this->user_id,
            "epoints" => $epoints,
            "create_time" => time(),
            "liushui_no" => $_POST['liushui_no'],
            "text" => $_POST['remark'],
            "status" => 0
        );

        M("ZhifubaoChongzhi")->add($data);

        $this->success("充值申请提交成功！", U("finance/alipay"));
    }

    public function delete_alipay() {
        $id = intval($_GET['id']);

        M("ZhifubaoChongzhi")->where(array("uid" => $this->user_id, "id" => $id, "status" => 0))->delete();

        $this->success("删除成功！", U("alipay"));
    }

    public function delete_cash() {
        $id = intval($_GET['id']);


        $info = M("TixianApply")->where(array("uid" => $this->user_id, "id" => $id, "status" => 0))->find();

        M("TixianApply")->where(array("uid" => $this->user_id, "id" => $id, "status" => 0))->delete();

        $fee = round($info['epoints'] * MC('cash_fee'), 2);

        if ($info['epoints'] > 0) {
            write_money($this->user_id, $info['epoints'], "取消提现", 6);
            write_money($this->user_id, $fee, "取消提现手续费返还", 6);
        }

        $this->success("删除成功！", U("cashing"));
    }

    /**
     * 提现申请
     */
    public function cash_action() {
        $epoints = intval($_POST['epoints']);

        $money = array(50, 100, 200, 500, 1000,3000);


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

        $count = M("TixianApply")->where(array("uid" => $this->user_id, "create_date" => get_date()))->count();

        if ($count >= MC('cash_num')) {
            $this->error("超过每日提现限制！");
        }



        $data = array(
            "uid" => $this->user_id,
            "epoints" => $epoints,
            "create_time" => time(),
            "create_date" => get_date(),
            "status" => 0
        );

        M("TixianApply")->add($data);

        write_money($this->user_id, -$epoints, "提现", 2);
        if($fee>0){
            write_money($this->user_id, -$fee, "提现手续费" . MC('cash_fee'), 2);
        }
        

        $this->success("提现申请提交成功！", U("finance/cashing"));
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
