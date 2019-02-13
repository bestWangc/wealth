<?php

/**
 * 晋级管理
 * @@CreateTime 2016-8-3 14:49:34
 * @version v1.0
 */
class PromotionAction extends CommonAction {

    public function index() {
        $this->nav = 8;
        $this->jibie_array = $this->get_jibie_array();

        $this->shenqing_array = M("JibieApply")
                ->where(array("uid" => $this->user_id))
                ->order("id desc")
                ->limit(10)
                ->select();

        $this->zhitui_num = $this->get_zhitui();
        $this->team_num = $this->get_sum();

        $this->display();
    }

    /**
     * 申请操作
     */
    public function index_action() {
        $jibie_id = intval($_POST['jibie_id']);
        $text = $_POST['remark'];


        if (M("JibieApply")->where(array("uid" => $this->user_id, "status" => 0))->count() > 0) {
            $this->error("您有一个晋级申请正在审核中，无法再次提交！");
        }


        $jibie = M("Jibie")->where(array("id" => $this->user_info['jibie_id']))->find();

        $jibie_info = M("Jibie")->where(array("id" => $jibie_id, "sort" => array("gt", $jibie['sort'])))->find();

        if (empty($jibie_info)) {
            $this->error("申请的级别不存在或者不满足条件！");
        }

        $zhitui_num = $this->get_zhitui();
        $team_num = $this->get_sum();

        if ($zhitui_num < $jibie_info['zhitui_num']) {
            $this->error("您不满足该级别要求的直推人数！");
        }

        if ($team_num < $jibie_info['team_num']) {
            $this->error("您不满足该级别要求的团队人数！");
        }

        $data = array(
            "uid" => $this->user_id,
            "jibie_id" => $jibie_id,
            "text" => $text,
            "create_time" => time()
        );

        M("JibieApply")->add($data);
    }

    /**
     * 获取直推人数
     * @return type
     */
    private function get_zhitui() {
        return M("Users")->where(array("main" => $this->user_id))->count();
    }

    /**
     * 获取团队人数
     */
    private function get_sum() {
        $sum_num = M("Users")->where("path like '%-{$this->user_id}-%'")->count();
        $sum_num+=1;
        return $sum_num;
    }

    /**
     * 获取级别数组
     */
    private function get_jibie_array() {
        $jibie = M("Jibie")->where(array("id" => $this->user_info['jibie_id']))->find();

        return M("Jibie")->where(array("sort" => array("gt", intval($jibie['sort']))))->order("sort,id desc")->select();
    }

}

/**
 * 获取级别的名称
 * @param type $id
 */
function get_jibie_title($id) {
    return M("Jibie")->where(array("id" => $id))->getField("title");
}

/**
 * 获取状态
 * @param type $id
 */
function get_status($id) {
    switch ($id) {
        case 1:
            return '审核通过';
            break;
        case 2:
            return '审核被拒绝';
            break;
        default:
            return '未审核';
            break;
    }
}

/**
 * 获取奖金发放情况
 * @param type $id
 */
function get_jiangjin_status($id) {
    switch ($id) {
        case 1:
            return '已发放';
            break;
        default:
            return '未发放';
            break;
    }
}
