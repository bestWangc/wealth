<?php
namespace app\index\controller;

use app\manage\model\UserRole;
use think\facade\Session;
use think\Db;

class Index extends Base
{

    public function index()
    {
        $user_role = Session::get('user_role');
        $role_name = $this->getUserRole($user_role);
        $this->assign([
            'uname' => Session::get('user_name'),
            'roleName' => $role_name,
            'urole' => $user_role
        ]);
        return $this->fetch();
    }

    public function indexMain()
    {
        $userName = Session::get('user_name');

        $field = 'su.created_date,su.email,sa.alipay_name,alipay_account,sad.`name`,sad.phone,sad.details';
        $info = Db::name('users')
            ->alias('su')
            ->join('alipay sa','sa.user_id = su.id','left')
            ->join('address sad','sad.user_id = su.id','left')
            ->where('su.parent_id',$this->uid)
            ->field($field)
            ->find();
        //团队成员数量
        $count = $this->getTeamInfo($this->uid);

        list($teamAllNum,$teamNewNum,$teamActiveNum) = $count;

        $orderCountInfo = $this->getTeamOrderCount($this->uid);

        $this->assign([
            'userName' => $userName,
            'createdDate' => $info['created_date'] ?? '未设置',
            'email' => $info['email'] ?? '未设置',
            'alipayName' => $info['alipay_name'] ?? '未设置',
            'alipayAccount' => $info['alipay_account'] ?? '未设置',
            'name' => $info['name'] ?? '未设置',
            'phone' => $info['phone'] ?? '未设置',
            'details' => $info['details'] ?? '未设置',
            'teamAllNum' => $teamAllNum,
            'teamNewNum' => $teamNewNum,
            'teamActiveNum' => $teamActiveNum,
            'orderTotal' => $orderCountInfo['total'],
            'moneyTotal' => $orderCountInfo['amount']
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
