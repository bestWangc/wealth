<?php
namespace app\index\controller;

use think\Db;
use think\facade\Request;

/**
 * 用户中心
 */
class User extends Base
{
    public function index()
    {
        $this->assign('nav',2);
        return $this->fetch();
    }

    public function edit()
    {
        $this->assign('nav',2);
        return $this->fetch();
    }

    public function doEdit(Request $request)
    {
        $data = [];
        $realName = $request::post('real_name');
        if(!empty($realName)){
            $data['real_name'] = $realName;
        }
        $alipayNo = $request::post('alipay_no');
        if(!empty($alipayNo)){
            $data['alipay_no'] = $alipayNo;
        }
        $nickName = $request::post('nick_name');
        if(!empty($nickName)){
            $data['nick_name'] = $nickName;
        }
        $phone = $request::post('phone');
        if(!empty($phone)){
            $data['mobile'] = $phone;
        }

        if(!empty($data)){
            $res = Db::name('users')
                ->where(array("id" => $this::$user_id))
                ->update($data);
            if($res){
                return jsonRes(0,'修改成功');
            }
        }
        return jsonRes(1,'修改失败，请重试');
    }

    public function edit_pwd()
    {
        $this->assign('nav',3);
        return $this->fetch();
    }

    public function edit_pwd_action()
    {
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

        Db::name("Users")->where(array("id"=>  $this->user_id))->save($data);
        
        $this->success("密码修改成功，请牢记您的新密码！",U("user/edit_pwd"));
    }

}
