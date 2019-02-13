<?php

/**
 * 充值管理
 *
 * @@CreateTime 2016-8-4 10:16:09
 * @version v1.0
 */
class RechargeAction extends CommonAction {

    public function money() {
        $this->list=M("AdminMoneyhistory")->order("id desc")->limit(50)->select();
        $this->display();
    }
    public function bi() {
        $this->list=M("AdminBihistory")->order("id desc")->limit(50)->select();
        $this->display();
    }
    
    /**
     * 钱包充值操作
     */
    public function money_action(){
        $user_name=$_POST['user_name'];
        $num=$_POST['epoints'];
        
        if($user_name==''){
            $this->error("请输入用户名！");
        }
        
        if($num==''||$num==0){
            $this->error("请输入需要充值的数量！");
        }
        
        $info=M("Users")->where(array("user_name"=>$user_name,"status"=>1))->find();
        
        if(empty($info)){
            $this->error("该会员不存在！");
        }
        write_money($info['id'], $num, "管理员操作", 3);
        write_admin_money($info['id'], $num);
        
        $this->success("充值成功！", U("money"));
    }
    
    
    /**
     * 钱包充值操作
     */
    public function bi_action(){
        $user_name=$_POST['user_name'];
        $num=$_POST['num'];
        
        if($user_name==''){
            $this->error("请输入用户名！");
        }
        
        if($num==''||$num==0){
            $this->error("请输入需要充值的数量！");
        }
        
        $info=M("Users")->where(array("user_name"=>$user_name,"status"=>1))->find();
        
        if(empty($info)){
            $this->error("该会员不存在！");
        }
//        write_money($info['id'], $num, "管理员操作", 3);
        M("Users")->where(array("id"=>$info['id'],"status"=>1))->setField("bi",$info['bi']+$num);
        
        write_admin_bi($info['id'], $num);
        
        $this->success("充值成功！", U("bi"));
    }

}
