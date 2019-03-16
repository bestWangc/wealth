<?php
namespace app\manage\controller;

use app\manage\model\UserRole;
use think\facade\Session;
use think\Db;

class Index extends Base
{

    public function index()
    {
        $this->assign([
            'uname' => Session::get('uname')
        ]);
        return $this->fetch();
    }

    public function indexMain()
    {

        $info = Db::name("users")
            ->where("status", 1)
            ->field('count(id) as count,sum(money) as money,sum(worker) as worker')
            ->find();

        $extractSum = Db::name("extract_apply")
            ->where("status", 0)
            ->sum("epoints");

        $this->assign([
            'userNum' => $info['count'],
            'moneyNum' => $info['money'],
            'workerNum' => $info['worker'],
            'extractSum' => $extractSum
        ]);
        return $this->fetch();
    }

    //后台用户管理
    public function adminUser()
    {
        return $this->fetch();
    }
}
