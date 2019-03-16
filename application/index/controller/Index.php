<?php
namespace app\index\controller;

use think\facade\Session;
use think\Db;

class Index extends Base
{

    public function index()
    {
        $role_name = self::getUserRole($this::$uid);
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
            'workerNum' => $userInfo['worker'],
            'money' => $userInfo['money'],
            'signStatus' => $signStatus,
            'yesterdayIncome' => $yesterdayIncome,
            'moneyHistory' => json_encode($moneyHistory)
        ]);
        return $this->fetch();
    }

    //获取角色权限名称
    public static function getUserRole($uid)
    {
        $user_role = Db::name('users')
            ->where('id',$uid)
            ->value('level_id');
        $roleName = Db::name('level')
            ->where('id',$user_role)
            ->value('title');
        return $roleName;
    }

    /**
     * 每日签到
     */
    public function sign()
    {
        $status = $this->getSignStatus($this::$uid);
        if ($status != 0) {
            $this->error("今日已签到");
        }

        $data = [
            "uid" => $this::$uid,
            "create_time" => time(),
            "sign_date" => get_date()
        ];
        $res = Db::name('daySign')->insert($data);

        $signIncome = getConfig('sign_income');
        writeMoney($this::$uid, $signIncome, "每日签到奖励", 5);

        if($res) return jsonRes(0,'签到成功');
        return jsonRes(0,'签到失败，请重试');
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

    /**
     * 下线推广
     */
    public function extend()
    {
        $userInfo = $this::$userInfo;
        if (!empty($userInfo['main'])) {
            $main_user_info = Db::name("users")
                ->where("id", $userInfo['main'])
                ->find();
        } else {
            $main_user_info = [];
        }

        $first_list = Db::name("users")
            ->where("main", $userInfo["id"])
            ->order("id")
            ->field('user_name,FROM_UNIXTIME(create_time) as create_time,money,worker')
            ->select();

        $sum_worker = Db::name("users")
            ->where("path", "like", "%{$userInfo["id"]}%")
            ->value('worker');

        $sum_money=Db::name("users")
            ->where("path", "like", "%{$userInfo["id"]}%")
            ->sum("money");

        $tuijian_list = Db::name("users")
            ->where("path", "like", "%{$userInfo["id"]}%")
            ->field('user_name,FROM_UNIXTIME(create_time) as create_time,money,path,worker')
            ->order("id asc")
            ->select();

        //二级推荐列表
        $second_list = [];
        //三级推荐列表
        $three_list = [];

        foreach ($tuijian_list as $key => $value) {
            $path_array = explode(",", $value['path']);
            if(isset($path_array[1]) && $path_array[1] == $userInfo['id']){
                $second_list[] = $value;
            }
            if(isset($path_array[2]) && $path_array[2] == $userInfo['id']){
                $three_list[] = $value;
            }
        }
        $siteUrl = getConfig('site_url');

        $this->assign([
            'siteUrl' => $siteUrl,
            'user_id' => $userInfo['id'],
            'main_user_info' => $main_user_info,
            'first_list' => json_encode($first_list),
            'sum_worker' => $sum_worker,
            'sum_money' => $sum_money,
            'second_list' => json_encode($second_list),
            'three_list' => json_encode($three_list)
        ]);
        return $this->fetch();
    }

    /**
     * 帮助
     * @return mixed
     */
    public function help()
    {
        $content = Article::getContentByID(2);
        $this->assign([
            'nav' => 11,
            'content' => $content,
            'panelName' => '帮助'
        ]);
        return $this->fetch('news');
    }

    /**
     * 关于
     * @return mixed
     */
    public function about()
    {
        $content = Article::getContentByID(1);
        $this->assign([
            'nav' => 9,
            'content' => $content,
            'panelName' => '关于'
        ]);
        return $this->fetch('news');
    }

    /**
     * 系统公告
     * @return mixed
     */
    public function news()
    {
        $content = Article::getContentByID(3);
        $this->assign([
            'content' => $content,
            'panelName' => '公告'
        ]);
        return $this->fetch();
    }
}
