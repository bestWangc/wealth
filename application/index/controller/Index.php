<?php
namespace app\index\controller;

use think\Db;
use think\facade\Request;

class Index extends Base
{

    public function index()
    {
        $yesterdayIncome = $this->getYesterdayIncome($this::$user_id);
        $signStatus = $this->getSignStatus($this::$user_id);
        $list = $this->getList($this::$user_id);

        $userInfo = Db::name("users")
            ->where("id", $this::$user_id)
            ->find();
        $jibie = get_jibie($userInfo['jibie_id']);
        $userInfo['jibie_name'] = is_array($jibie) ? $jibie['title'] : $jibie;

        $this->assign([
            'nav' => 1,
            'yesterdayIncome' => $yesterdayIncome,
            'signStatus' => $signStatus,
            'list' => $list
        ]);
        return $this->fetch();
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
            $main_user_info = array();
        }

        $first_list = Db::name("users")
            ->where("main", $userInfo["id"])
            ->order("id")
            ->select();

        $sum_bi = Db::name("users")
                ->where("path", "like", "%{$userInfo["id"]}%")
                ->sum("bi");
        
        $sum_money=Db::name("users")
                ->where("path", "like", "%{$userInfo["id"]}%")
                ->sum("money");
        
        $tuijian_list = Db::name("users")
                ->where("path", "like", "%{$userInfo["id"]}%")
                ->order("id asc")
                ->select();

        //二级推荐列表
        $second_list = [];
        //三级推荐列表
        $three_list = [];

        foreach ($tuijian_list as $key => $value) {
            // $path_str = str_replace("-", '', $value['path']);
            $path_array = explode(",", $value['path']);
            if(isset($path_array[1]) && $path_array[1] == $userInfo['id']){
                $second_list[] = $value;
            }
            if(isset($path_array[2]) && $path_array[2] == $userInfo['id']){
                $three_list[] = $value;
            }
        }

        $this->assign([
            'nav' => 12,
            'user_id' => $userInfo['id'],
            'main_user_info' => $main_user_info,
            'first_list' => $first_list,
            'sum_bi' => $sum_bi,
            'sum_money' => $sum_money,
            'second_list' => $second_list,
            'three_list' => $three_list
        ]);
        return $this->fetch();
    }

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

    //系统公告
    public function news()
    {
        $content = Article::getContentByID(3);
        $this->assign([
            'nav' => 10,
            'content' => $content,
            'panelName' => '公告'
        ]);
        return $this->fetch();
    }

    /**
     * 获取最近10笔收支记录
     * @param $uid
     * @return array|\PDOStatement|string|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    private function getList($uid) {
        $res = Db::name("moneyhistory")
            ->where('uid',$uid)
            ->order("id desc")
            ->limit(10)
            ->select();
        return $res;
    }

    /**
     * 每日签到
     */
    public function sign()
    {
        $status = $this->getSignStatus($this::$user_id);
        if ($status != 0) {
            $this->error("今日已签到");
        }

        $data = [
            "uid" => $this::$user_id,
            "create_time" => time(),
            "sign_date" => get_date()
        ];
        $res = Db::name('daySign')->insert($data);

        $this->writeMoney($this::$user_id, $this::$signIncome, "每日签到奖励", 5);

        if($res) return jsonRes(0,'签到成功');
        return jsonRes(0,'签到失败，请重试');
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

        $info = Db::name("dailyExecute")->where($where)->sum("epoints");

        $res = '未分红';
        if (!empty($info)) {
            $res = $info;
        }
        return $res;
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

        $count = Db::name("daySign")->where($where)->count();
        if ($count >= 1) {
            $status = 1;
        } else {
            $status = 0;
        }
        return $status;
    }

}
