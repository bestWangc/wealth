<?php

/**
 * 提现管理
 *
 * @@CreateTime 2016-8-4 13:38:40
 * @version v1.0
 */
class TixianApplyAction extends CommonAction {

    public function index() {
        parent::index(20, "id desc");
    }
    
    public function update() {
        $id=intval($_POST['id']);
        $status=  intval($_POST['status']);
        
        $data=array(
            "status"=>$status,
            "update_time"=>time(),
            "update_admin_id"=>  session("ly_id")
        );
        M(MODULE_NAME)->where(array("id"=>$id))->save($data);
        
        $this->success("编辑成功！");
    }
    
}



function get_zfb_status($id){
    switch ($id) {
        case 1:
            return "<font color='red'>已通过</font>";
            break;
        default:
            return '未审核';
            break;
    }
}
