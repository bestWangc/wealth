<?php
namespace app\admins;

/**
 * 支付宝充值管理
 *
 */
class ZhifubaoChongzhi extends Base
{

    public function index() {
        parent::index(20, "id desc");
    }

    public function update() {
        $id = intval($_POST['id']);
        $status = intval($_POST['status']);

        if ($status == 1) {
            $info = M(MODULE_NAME)->where(array("id" => $id))->find();
            write_money($info['uid'], $info['epoints'], "充值", 3);
            
            $path=M("Users")->where(array("id"=>$info['uid']))->getField("path");

            //写入每级推荐奖励
            if (!empty($path)) {
                $path = str_replace("-", '', $path);
                $path_array = explode(',', $path);
                //一级推荐人
                if (!empty($path_array[0])) {
                    write_money($path_array[0], round($info['epoints'] * MC('first_income'), 2), "一代直推奖励" . (MC('first_income') * 100) . '%', 4);
                }
                //二级推荐人
                if (!empty($path_array[1])) {
                    write_money($path_array[1], round($info['epoints'] * MC('second_income'), 2), "二代奖励" . (MC('second_income') * 100) . '%', 4);
                }
                //三级推荐人
                if (!empty($path_array[2])) {
                    write_money($path_array[2], round($info['epoints'] * MC('three_income'), 2), "三代奖励" . (MC('three_income') * 100) . '%', 4);
                }
            }
        }

        $data = array(
            "status" => $status,
            "action_time" => time()
        );
        M(MODULE_NAME)->where(array("id" => $id))->save($data);

        $this->success("编辑成功！");
    }

}

function get_zfb_status($id) {
    switch ($id) {
        case 1:
            return "<font color='red'>已通过</font>";
            break;
        case 2:
            return "<font color='blue'>已拒绝</font>";
            break;
        default:
            return '未审核';
            break;
    }
}
