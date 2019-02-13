<?php
namespace app\admins;

/**
 * 晋级申请管理
 *
 */
class JibieApply extends Base
{

    public function index() {
        parent::index(20, "id desc");
    }

    public function update() {
        $id = intval($_POST['id']);
        $status = intval($_POST['status']);

        $shenqing_info = M(MODULE_NAME)->where(array("id" => $id))->find();

        if ($shenqing_info['status'] == 0) {
            $jibie_info = M("Jibie")->where(array("id" => $shenqing_info['jibie_id']))->find();

            if ($status == 1) {
                write_money($shenqing_info['uid'], $jibie_info['bonus_amount'], "晋级{$jibie_info['title']}奖励", 4);
                M("Users")->where(array("id"=>$shenqing_info['uid']))->setField("jibie_id",$jibie_info['id']);
            }

            $data = array(
                "status" => $status,
                "action_status" => 1
            );
            M(MODULE_NAME)->where(array("id"=>$id))->save($data);
        }


        $this->success("编辑成功！");
    }

}

function get_jb_status($id) {
    switch ($id) {
        case 1:
            return '审核通过';
            break;
        case 2:
            return '拒绝';
            break;
        default:
            return '未审核';
            break;
    }
}