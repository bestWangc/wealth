<?php
//系统管理控制器类


class SystemAction extends Action{
	
	public function out()   //退出系统
	{
		import("ORG.Util.Session");
                $_SESSION['ly_name']='';
                $_SESSION['ly_id']='';
                import('ORG.Util.Session');
                //清空session数据
		Session::clear();
                //销毁session变量
		Session::destroy();
                echo '<script type="text/javascript">parent.location.href="'.U('/index').'";</script>';
	}


        Public function check() {
        if ($this->isAjax()) {
            $admin_name = addslashes($_POST['admin_name']);
            $admin_pwd = md5($_POST['admin_pwd']);
            $M = M('Admin');
            $info = $M->where("ly_name='{$admin_name}'")->find();
            unset($M);
            if (empty($info)) {
                $this->ajaxReturn(1); //用户不存在
            } else {
                if ($admin_pwd == $info['ly_pwd']) {
                    session('ly_name', $info['ly_name']);
                    session('ly_id',$info['ly_id']);
                    $this->ajaxReturn(2);  //登录成功
                } else {
                    $this->ajaxReturn(3); //密码错误
                }
            }
        } else {
            echo '数据请求错误！请刷新网页后重新登录！';
        }
    }
}
?>