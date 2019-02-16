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
        $this->nav = 12;

        if (!empty($this->user_info['main'])) {
            $this->main_user_info = Db::name("Users")->where(array("id" => $this->user_info['main']))->find();
        } else {
            $this->main_user_info = array();
        }


        $this->first_list = Db::name("Users")->where(array("main" => $this->user_info["id"]))->order("id")->select();

        
        $sum_bi = Db::name("Users")
                ->where(array("path" => array("like", "%-{$this->user_info["id"]}-%")))
                ->sum("bi");
        $this->sum_bi=$sum_bi;
        
        $sum_money=Db::name("Users")
                ->where(array("path" => array("like", "%-{$this->user_info["id"]}-%")))
                ->sum("money");
        $this->sum_money=$sum_money;
        
        
        
        $tuijian_list = Db::name("Users")
                ->where(array("path" => array("like", "%-{$this->user_info["id"]}-%")))
                ->order("id")
                ->select();
        //二级推荐列表
        $second_list = [];
        //三级推荐列表
        $three_list = [];

        $user_id = $this->user_info['id'];

        foreach ($tuijian_list as $key => $value) {
            $path_str=  str_replace("-", '', $value['path']);
            $path_array=  explode(",", $path_str);
            if($path_array[1]==$user_id){
                $second_list[]=$value;
            }
            if($path_array[2]==$user_id){
                $three_list[]=$value;
            }
        }
        
        $this->second_list=$second_list;
        $this->three_list=$three_list;

        return $this->fetch();
    }

    public function help()
    {
        $this->nav = 11;
        $this->content = Db::name("Article")
            ->where("kind_id", 2)
            ->getField("content");
        return $this->fetch();
    }

    public function about() {
        $this->nav = 9;
        $this->content = M("Article")
            ->where("kind_id", 1)
            ->getField("content");
        return $this->fetch();
    }

    public function news() {
        $this->nav = 10;
        $this->content = Db::name("Article")
            ->where("kind_id", 3)
            ->getField("content");
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
