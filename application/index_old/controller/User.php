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

    //修改密码
    public function edit_pwd()
    {
        $this->assign('nav',3);
        return $this->fetch();
    }

    public function doEditPwd(Request $request)
    {
        $type = $request::post('type/d');

        $userInfo = $this::$userInfo;
        if(empty($type)){
            return jsonRes(1,'未知错误，请重试');
        }

        switch ($type) {
            case 1:  //登录密码
                $sys_old_pwd = $userInfo['user_pwd'];
                $field="user_pwd";
                break;
            case 2: //二次密码
                $sys_old_pwd = $userInfo['user_pwd1'];
                $field="user_pwd1";
                break;
        }
        $oldPWD = $request::post('old_pwd');
        $newPWD = $request::post('new_pwd');
        $reNewPWD = $request::post('re_new_pwd');

        if(empty($oldPWD)) return jsonRes(1,'原密码密码不能为空');
        if(empty($newPWD)) return jsonRes(1,'新密码密码不能为空');
        if($newPWD != $reNewPWD) return jsonRes(1,'两次密码输入不一致');

        if($sys_old_pwd != md5($oldPWD.'jstj')){
            return jsonRes(1,'原密码不正确');
        }
        
        $data = [
            $field => md5($newPWD.'jstj')
        ];

        $res = Db::name("users")
            ->lock()
            ->where("id", $this::$user_id)
            ->update($data);
        if($res){
            return jsonRes(0,'密码修改成功，请牢记您的新密码');
        }
        return jsonRes(1,'密码修改失败，请稍后再试');
    }

}
