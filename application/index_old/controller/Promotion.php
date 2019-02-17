<?php
namespace app\index_old\controller;
use think\Db;
use think\db\Where;
use think\facade\Request;

class Promotion extends Base
{

    public function index()
    {
        $userInfo = $this::$userInfo;
        $level = $this->getLevel($userInfo['level_id']);

        $apply = Db::name("level_apply")
                ->alias('la')
                ->join('level l','l.id = la.level_id')
                ->where("uid", $this::$user_id)
                ->field('la.create_time,la.status,la.action_status,l.title')
                ->order("la.id desc")
                ->limit(10)
                ->select();

        $firstChildCount = $this->getFirstChild($this::$user_id);
        $teamPersonCount = $this->getTeamPersonCount($this::$user_id);

        $this->assign([
            'nav' => 8,
            'apply' => $apply,
            'level' => $level,
            'firstChildCount' => $firstChildCount,
            'teamPersonCount' => $teamPersonCount
        ]);
        return $this->fetch();
    }

    /**
     * 申请操作
     */
    public function doApply(Request $request)
    {
        $level_id = $request::post('level_id/d');
        $remark = $request::post('remark');
        if(empty($level_id)){
            return jsonRes(1,'请选择级别');
        }
        $isHasApply = Db::name("level_apply")
            ->where("uid", $this::$user_id)
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

        $firstChildCount = $this->getFirstChild($this::$user_id);
        $teamPersonCount = $this->getTeamPersonCount($this::$user_id);

        if ($firstChildCount < $levelInfo['first_num']) {
            return jsonRes(1,'您不满足该级别要求的直推人数');
        }

        if ($teamPersonCount < $levelInfo['team_num']) {
            return jsonRes(1,'您不满足该级别要求的团队人数');
        }

        $data = [
            "uid" => $this::$user_id,
            "level_id" => $level_id,
            "text" => $remark,
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