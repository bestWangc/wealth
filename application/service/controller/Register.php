<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018-8-11
 * Time: 10:48
 */

namespace app\service\controller;

use app\tools\M3result;
use think\facade\Request;
use think\Db;

class Register extends Base
{
    public function index(Request $request)
    {
        $tuijian_switch = MC("tuijian_switch");

        $parent_id = $request::param('parent_id');

        if(empty($parent_id)){
            return jsonRes(1,'推荐人不正确或不存在');
        }
        $parentInfo = Db::name("users")
            ->where('status',1)
            ->where('id',$parent_id)
            ->find();
        if ($tuijian_switch) {
            if (empty($parentInfo)) {
                return jsonRes(1,'推荐人不正确或不存在');
            }
        }

        $userName = $request::param('username', "");
        $userPwd = $request::param('userpwd', "");
        $secondPwd = $request::param('secondPwd', "");

        if(empty($userName) || empty($userPwd) || empty($secondPwd)){
            return jsonRes(1,'信息填写不全，请重试');
        }
        $oldUserInfo = Db::name('users')
            ->where('user_name',$userName)
            ->count('id');

        if(!!$oldUserInfo){
            return jsonRes(1,'帐号已存在，请重新填写');
        }

        $data = [
            'name' => $userName,
            'passwd' => md5($userPwd.'jfn'),
            'role' => 2,
            'email' => $email,
            'photo' => '/uploads/photo_1.jpg',
            'parent_id' => $parent_id ? $parent_id : 1,
            'created_date' => time()
        ];
        $creatUser = Db::name('users')->insert($data);

        if($creatUser){
            if(!empty($parent_id) && $parent_id != 1){
                $role = 0;
                $parentCount = Db::name('users')
                    ->where('parent_id',$parent_id)
                    ->count('id');
                if($parentCount > 5 && $parentCount <= 15){
                    $role = 3;
                }
                if($parentCount > 15 && $parentCount <= 30){
                    $role = 4;
                }
                if($parentCount > 30 && $parentCount <= 50){
                    $role = 5;
                }
                if($role){
                    Db::name('users')
                        ->where('id',$parent_id)
                        ->update(['role'=>$role]);
                }
            }

            return jsonRes(0,'注册成功');
        }

        return jsonRes(1,'注册失败，请重试');
    }
}