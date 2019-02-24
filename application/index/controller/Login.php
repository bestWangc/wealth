<?php
namespace app\index\controller;

use think\Controller;
use think\facade\Request;
use think\Db;

class Login extends Controller
{
    public function index()
    {
        return $this->fetch();
    }

    /**
     * 注册
     * @param Request $request
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function register(Request $request)
    {
        $uid = $request::param('uuu989uid/d');
        $pName = '';
        $pId = 0;
        if (!empty($uid)) {
            $info = Db::name("users")
                ->where('id',$uid)
                ->field('id,user_name')
                ->find();
            $pName = $info['user_name'];
            $pId = $info['id'];
        }
        $this->assign([
            'pName' => $pName,
            'pId' => $pId
        ]);

        return $this->fetch();
    }
}
