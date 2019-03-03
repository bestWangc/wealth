<?php
namespace app\manage\controller;

use think\Db;
use think\facade\Session;
use think\facade\Request;

class User extends Base
{

    public function index()
    {
        $adminList = Db::name('admin')
            ->field('id,name')
            ->order('sort desc')
            ->select();
        $this->assign('adminList', json_encode($adminList,JSON_UNESCAPED_UNICODE));
        return $this->fetch();
    }

    /**
     * 添加admin 用户
     * @param Request $request
     * @return \think\response\Json
     */
    public function saveAdmin(Request $request)
    {
        $userName = $request::post('username');
        $userPwd = $request::post('userpwd');
        if(empty($userName) || empty($userPwd)){
            return jsonRes(1,'信息填写不全，请重试');
        }
        $oldUserInfo = Db::name('admin')
            ->where('name',$userName)
            ->count('id');

        if(!!$oldUserInfo){
            return jsonRes(1,'帐号已存在，请重新填写');
        }
        $data = [
            'name' => $userName,
            'password' => md5($userPwd.'jstj'),
            'sort' => 1
        ];

        $creatUser = Db::name('admin')->insert($data);

        if($creatUser) return jsonRes(0,'添加成功');

        return jsonRes(1,'添加失败，请重试');
    }

    public function delAdmin(Request $request)
    {
        $adminId = $request::post('admin_id/d');
        if(!empty($adminId)){
            $res = Db::name('admin')
                ->where('id',$adminId)
                ->delete();
            if($res){
                return jsonRes(0,'删除成功');
            }else{
                return jsonRes(1,'该管理员不存在');
            }
        }
        return jsonRes(1,'删除失败，请重试');
    }

    //会员列表
    public function user()
    {
        return $this->fetch();
    }

    //会员详细信息
    public function userDetails()
    {
        $userInfo = Db::name('users')
            ->alias('u')
            ->join('level l','l.id = u.level_id','left')
            ->where('status',1)
            ->field('u.id,u.user_name,u.real_name,u.mobile,u.money,u.coin,u.login_count,u.create_time,l.title')
            ->order('u.create_time desc')
            ->select();

        if(!empty($userInfo)){
            foreach ($userInfo as $k => &$v) {
                $v['create_time'] = date('Y-m-d H:i:s',$v['create_time']);
            }
        }

        return jsonRes(0,'success',$userInfo);
    }
}
