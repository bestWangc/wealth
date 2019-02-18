<?php
namespace app\index\controller;

use think\facade\Session;
use think\Db;

class Index extends Base
{

    public function index()
    {
        $role_name = $this->getUserRole($this::$uid);
        $this->assign([
            'uname' => Session::get('user_name'),
            'roleName' => $role_name
        ]);
        return $this->fetch();
    }

    public function indexMain()
    {

        $signStatus = $this->getSignStatus($this::$uid);
        $userInfo = $this::$userInfo;
        $yesterdayIncome = $this->getYesterdayIncome($this::$uid);
        $moneyHistory = MoneyHistry::getHistoryByLimit($this::$uid,10);
        foreach ($moneyHistory as $key => &$value){
            $value['create_time'] = date('Y-m-d H:i',$value['create_time']);
        }
        $this->assign([
            'coin' => $userInfo['coin'],
            'money' => $userInfo['money'],
            'signStatus' => $signStatus,
            'yesterdayIncome' => $yesterdayIncome,
            'moneyHistory' => json_encode($moneyHistory)
        ]);
        return $this->fetch();
    }

    //获取角色权限名称
    public function getUserRole($uid)
    {
        $user_role = Db::name('users')
            ->where('id',$uid)
            ->value('level_id');
        $roleName = Db::name('level')
            ->where('id',$user_role)
            ->value('title');
        return $roleName;
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

    /**
     * 获取当日签到的状态,0-未签到 1-已签到
     *
     * @param $uid
     * @return int
     */
    private function getSignStatus($uid)
    {
        $where = [
            "uid" => $uid,
            "sign_date" => get_date()
        ];

        $count = Db::name("day_sign")->where($where)->count();
        if ($count >= 1) {
            $status = 1;
        } else {
            $status = 0;
        }
        return $status;
    }

    /**
     * 获取昨日收益
     * @param $uid
     * @return float|string
     */
    private function getYesterdayIncome($uid)
    {
        $where = [
            "create_date" => date("Y-m-d", strtotime("-1 day")),
            "uid" => $uid,
        ];
        $info = Db::name("daily_execute")->where($where)->sum("epoints");

        $res = '未分红';
        if (!empty($info)) {
            $res = $info;
        }
        return $res;
    }
}
