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
            ->field('count(id) as count,sum(money) as money,sum(coin) as coin')
            ->find();

        $extractSum = Db::name("extract_apply")
            ->where("status", 0)
            ->sum("epoints");

        $this->assign([
            'userNum' => $info['count'],
            'moneyNum' => $info['money'],
            'coinNum' => $info['coin'],
            'extractSum' => $extractSum
        ]);
        return $this->fetch();
    }

    //获取角色权限名称
    public function getUserRole($user_role)
    {
        $roleName = UserRole::where('id',$user_role)->field('role_name')->find();
        return $roleName['role_name'];
    }

    //计算团队成员数量
    public function getTeamInfo($uid)
    {
        //今日凌晨时间戳
        $time = strtotime(date('Ymd'));
        //今日新增成员
        $sql1 = 'SELECT count(id) FROM ssc_users WHERE parent_id = '.$uid.' AND created_date > '.$time;
        //今日活跃成员
        $sql2 = 'SELECT count(DISTINCT su.id) AS count3 FROM ssc_users su
                INNER JOIN ssc_order so ON so.user_id = su.id AND so.created_date > '.$time.'
                WHERE su.parent_id = '.$uid;
        $result = Db::name('users')
            ->field('count(id) as count')
            ->where('parent_id',$uid)
            ->unionAll($sql1)
            ->unionAll($sql2)
            ->select();
        $result = array_column($result,'count');
        return $result;
    }

    //获取简单收益信息
    public function getTeamOrderCount($uid)
    {
        //今日凌晨时间戳
        $time = strtotime(date('Ymd'));
        $result = Db::name('order')
            ->alias('so')
            ->join('users su','su.id = so.user_id')
            ->field('COUNT(so.id) AS total,COALESCE(SUM(so.amount),0) AS amount')
            ->where('su.parent_id',$uid)
            ->where('so.created_date','>', $time)
            ->find();
        return $result;
    }

}
