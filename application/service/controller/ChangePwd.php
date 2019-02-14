<?php
namespace app\service\controller;

use think\facade\Request;
use think\Db;

class ChangePwd extends Base
{
    public function index(Request $request)
    {
        $uid = $request::param('uid', 0);
        $uname = $request::param('uname', "");
        $oldPwd = $request::param('oldPwd', "");
        $newPwd = $request::param('newPwd', "");
        $reNewPwd = $request::param('reNewPwd', "");

        if(!$uid || empty($uname) || empty($oldPwd) || empty($newPwd) || empty($reNewPwd)){
            return jsonRes(1,'信息填写不全，请重试');
        }
        if($newPwd !== $reNewPwd){
            return jsonRes(1,'两次密码输入不一致，请重试');
        }
        if(strlen($newPwd) < 6){
            return jsonRes(1,'密码长度不能少于6位');
        }

        $newPwd = md5($newPwd.'jfn');

        $userArr = Db::name('users')
            ->where('id',$uid)
            ->where('status',1)
            ->field('passwd')
            ->find();
        if(!empty($userArr)){
            if(md5($oldPwd.'jfn') == $userArr['passwd']){
                $res = Db::name('users')->where('id',$uid)->setField('passwd', $newPwd);
                if(!!$res){
                    return jsonRes(0,'修改成功');
                }
            }
        }

        return jsonRes(1,'修改失败，请重试');
    }
}