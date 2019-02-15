<?php

/**
 *
 * @@CreateTime 2016-8-3 9:17:39
 * @version v1.0
 */
class MainAction extends CommonAction {

    public function index() {
        $this->nav = 1;
        $this->yesterday_income = $this->get_yesterday_income();
        $this->sign_status = $this->get_sign_status();
        $this->list = $this->get_list();
        $this->display();
    }

    /**
     * 下线推广
     */
    public function extend() {
        $this->nav = 12;


        if (!empty($this->user_info['main'])) {
            $this->main_user_info = M("Users")->where(array("id" => $this->user_info['main']))->find();
        } else {
            $this->main_user_info = array();
        }


        $this->first_list = M("Users")->where(array("main" => $this->user_info["id"]))->order("id")->select();

        
        $sum_bi=M("Users")
                ->where(array("path" => array("like", "%-{$this->user_info["id"]}-%")))
                ->sum("bi");
        $this->sum_bi=$sum_bi;
        
        $sum_money=M("Users")
                ->where(array("path" => array("like", "%-{$this->user_info["id"]}-%")))
                ->sum("money");
        $this->sum_money=$sum_money;
        
        
        
        $tuijian_list = M("Users")
                ->where(array("path" => array("like", "%-{$this->user_info["id"]}-%")))
                ->order("id")
                ->select();
        //二级推荐列表
        $second_list = array();
        //三级推荐列表
        $three_list = array();


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



        $this->display();
    }

    public function help() {
        $this->nav = 11;
        $this->content = M("Article")->where(array("kind_id" => 2))->getField("content");
        $this->display();
    }

    public function about() {
        $this->nav = 9;
        $this->content = M("Article")->where(array("kind_id" => 1))->getField("content");
        $this->display();
    }

    public function news() {
        $this->nav = 10;
        $this->content = M("Article")->where(array("kind_id" => 3))->getField("content");
        $this->display();
    }

    /**
     * 获取最近10笔收支记录
     */
    private function get_list() {
        $where = array(
            "uid" => $this->user_id,
        );
        return M("Moneyhistory")->where($where)->order("id desc")->limit(10)->select();
    }

    /**
     * 每日签到
     */
    public function sign() {
        $status = $this->get_sign_status();
        if ($status != 0) {
            $this->error("今日已经签到过！");
        }

        $data = array(
            "uid" => $this->user_id,
            "create_time" => time(),
            "sign_date" => get_date()
        );
        M("DaySign")->add($data);

        write_money($this->user_id, MC("sign_income"), "每日签到奖励", 5);

        $this->success("签到成功!", U("/main"));
    }

    /**
     * 获取昨日收益
     */
    private function get_yesterday_income() {
        $where = array(
            "create_date" => date("Y-m-d", strtotime("-1 day")),
            "uid" => $this->user_id,
        );

        $info = M("DailyExecute")->where($where)->sum("epoints");


        if (empty($info)) {
            return '未分红';
        } else {
            return $info;
        }
    }

    /**
     * 获取当日签到的状态
     * 
     * 0-未签到
     * 1-已签到
     */
    private function get_sign_status() {
        $where = array(
            "uid" => $this->user_id,
            "sign_date" => get_date()
        );

        if (M("DaySign")->where($where)->count() >= 1) {
            return 1;
        } else {
            return 0;
        }
    }

}
