<?php
namespace app\index\controller;

use think\Db;
use think\facade\Request;

class Level extends Base
{
    public function index()
    {
        $userInfo = $this::$userInfo;
        $level = $this->getLevel($userInfo['level_id']);

        $apply = Db::name("level_apply")
            ->alias('la')
            ->join('level l','l.id = la.level_id')
            ->where("uid", $this::$uid)
            ->field('FROM_UNIXTIME(la.create_time) as create_time,la.status,la.action_status,l.title')
            ->order("la.id desc")
            ->select();

        $firstChildCount = $this->getFirstChild($this::$uid);
        $teamPersonCount = $this->getTeamPersonCount($this::$uid);

        $this->assign([
            'apply' => json_encode($apply,JSON_UNESCAPED_UNICODE),
            'level' => $level,
            'firstChildCount' => $firstChildCount,
            'teamPersonCount' => $teamPersonCount
        ]);
        return $this->fetch();
    }

    /**
     * 申请操作
     * @param Request $request
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function doApply(Request $request)
    {
        $level_id = $request::post('level_id/d');
        if(empty($level_id)){
            return jsonRes(1,'请选择级别');
        }
        $isHasApply = Db::name("level_apply")
            ->where("uid", $this::$uid)
            ->where('status',0)
            ->count('id');

        if ($isHasApply > 0) {
            return jsonRes(1,'您有一个晋级申请正在审核中，请稍候提交');
        }

        $userInfo = $this::$userInfo;
        $currentLevel = Db::name("level")
            ->where("id", $userInfo['level_id'])
            ->value('sort');

        $levelInfo = Db::name("level")
            ->where("id", $level_id)
            ->where("sort",">",$currentLevel)
            ->find();

        if (empty($levelInfo)) {
            return jsonRes(1,'申请的级别不存在或者不满足条件');
        }

        $firstChildCount = $this->getFirstChild($this::$uid);
        $teamPersonCount = $this->getTeamPersonCount($this::$uid);

        if ($firstChildCount < $levelInfo['first_num']) {
            return jsonRes(1,'您不满足该级别要求的直推人数');
        }

        if ($teamPersonCount < $levelInfo['team_num']) {
            return jsonRes(1,'您不满足该级别要求的团队人数');
        }

        $data = [
            "uid" => $this::$user_id,
            "level_id" => $level_id,
            "create_time" => time()
        ];

        $res = Db::name("level_apply")->insert($data);
        if($res){
            return jsonRes(0,'申请成功，请等待审核');
        }
        return jsonRes(1,'位置错误，请重试');
    }

    /**
     * 获取直推人数
     * @param $uid
     * @return float|string
     */
    private function getFirstChild($uid)
    {
        $res = Db::name("users")
            ->where("main", $uid)
            ->count();
        return $res;
    }

    /**
     * 获取团队人数
     * @param $uid
     * @return float|int|string
     */
    private function getTeamPersonCount($uid)
    {
        $sum_num = Db::name("users")
            ->where("path", "like", "%{$uid}%")
            ->count();
        $sum_num += 1;
        return $sum_num;
    }

    /**
     * 获取级别数组
     * @param $levelID
     * @return array|\PDOStatement|string|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    private function getLevel($levelID)
    {
        $level = Db::name("level")
            ->where("id", $levelID)
            ->value('sort');
        $res = Db::name("level")
            ->where("sort",'>' , intval($level))
            ->order("sort,id desc")
            ->select();
        return $res;
    }
}
