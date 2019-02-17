<?php
//系统管理控制器
class AdminAction extends CommonAction{
    
    function index(){
        parent::index(20, 'ly_id desc');
    }
    
    /**
     * 保存修改密码操作 
     */
    function update() {
        if ($this->isAjax()) {
            $admin_id = intval($_POST['admin_id']);
            $admin_pwd = md5($_POST['admin_pwd']);
            $data = array(
                'ly_id' => $admin_id,
                'ly_pwd' => $admin_pwd
            );
            $M = M(MODULE_NAME);
            $id = $M->save($data);
            unset($M);
            if ($id)
                $this->ajaxReturn(1);
            else
                $this->ajaxReturn(0);
        }
        else {
            echo '<font color="red">数据请求错误！请刷新网页后重新修改密码！</font>';
        }
    }


    /*检测用户名是否存在*/
    function check_admin_name(){
        if($this->isAjax()){
            $admin_name=addslashes($_POST['admin_name']);
            $M=M(MODULE_NAME);
            if($M->where("ly_name='{$admin_name}'")->count()>0)
                $this->ajaxReturn(1);  //用户存在
            else       
                $this->ajaxReturn(2);  //用户不存在
        }
        else
            {
            echo '<font color="red">请求错误！请正确进行数据提交！</font>';
        }

    }


    function save_add_admin(){
        if($this->isAjax()){
            $admin_name=addslashes($_POST['admin_name']);
            $admin_pwd=md5($_POST['admin_pwd']);
            if($admin_name=='' || $admin_pwd==''){
                //用户名或密码不能为空
                $this->ajaxReturn(0);
            }
            else{
                $M=M(MODULE_NAME);
                if($M->where("ly_name='{$admin_name}'")->count()>0){
                    //用户已经存在
                    $this->ajaxReturn(1);
                }
                else{
                    $data=array(
                        'ly_name'=>$admin_name,
                        'ly_pwd'=>$admin_pwd
                    );
                    $id=$M->add($data);
                    unset($M);
                    if($id){
                        //添加成功
                        $this->ajaxReturn(2);
                    }else{
                        //添加失败
                        $this->ajaxReturn(3);
                    }
                }
            }

        }else{
            echo '数据请求错误！请正确进行数据请求！';
        }
    }

    //删除管理员
    function del_admin(){
        $Admin=M(MODULE_NAME);
        $count=$Admin->count();
        if($this->isAjax()){
            $admin_id=addslashes($_POST['admin_id']);
            if($count<=1){
                $this->ajaxReturn(2,$admin_id);
            }else{
                $Admin->where("ly_id={$admin_id}")->delete();
                $this->ajaxReturn(1,$admin_id);
            }
        }else{
            echo '数据请求错误！请重新刷新后重新删除！';
        }
    }

}
?>
