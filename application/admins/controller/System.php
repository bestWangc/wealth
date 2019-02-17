<?php
namespace app\admins\controller;


use think\Controller;
use think\facade\Request;

/**
 * 系统管理控制器类
 * Class System
 * @package app\admins
 */
class System extends Controller
{
	
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


	public function check(Request $request)
    {
        if ($request::isAjax()) {
            $admin_name = addslashes($_POST['admin_name']);
            $admin_pwd = md5($_POST['admin_pwd']);
            $info = db('admin')->where("ly_name='{$admin_name}'")->find();
            unset($M);
            if (empty($info)) {
                return json(['data'=>1]); //用户不存在
            } else {
                if ($admin_pwd == $info['ly_pwd']) {
                    session('ly_name', $info['ly_name']);
                    session('ly_id',$info['ly_id']);

                    return json(['data'=>2]);
                } else {
                    return json(['data'=>3]);
                }
            }
        } else {
            echo '数据请求错误！请刷新网页后重新登录！';
        }
    }
}