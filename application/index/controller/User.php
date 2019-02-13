<?php
namespace app\index;

/**
 * 用户中心
 */
class User extends Base
{

    public function index() {
        $this->nav = 2;
        $this->display();
    }

    public function edit() {
        $this->nav = 2;
        $this->display();
    }
    
    public function edit_pwd() {
        $this->nav = 3;
        $this->display();
    }

    public function edit_action() {
        $M = M("Users");

        $data = array(
            "real_name" => $_POST['real_name'],
            "zhifubao_no" => $_POST['zhifubao_no'],
            "nick_name" => $_POST['nick_name'],
            "mobile" => $_POST['mobile'],
        );


        $status = $M->where(array("id" => $this->user_id))->save($data);

        $this->success("修改成功！", U("user/index"));
    }

    public function edit_pwd_action() {
        $type = intval($_POST['type']);

        switch ($type) {
            case 1:  //登录密码
                $sys_old_pwd = $this->user_info['user_pwd'];
                $field="user_pwd";
                break;
            case 2: //二次密码
                $sys_old_pwd = $this->user_info['user_pwd1'];
                $field="user_pwd1";
                break;
        }
        
        if($sys_old_pwd!=md5($_POST['old_pwd'])){
            $this->error("原密码不正确！");
        }
        
        if($_POST['new_pwd']==''){
            $this->error("请填写新密码！");
        }
        
        if($_POST['new_pwd']!=$_POST['new_pwd1']){
            $this->error("新密码两次填写不一致！");
        }
        
        $data=array(
            $field=>md5($_POST['new_pwd'])
        );
        
        M("Users")->where(array("id"=>  $this->user_id))->save($data);
        
        $this->success("密码修改成功，请牢记您的新密码！",U("user/edit_pwd"));
    }

}
