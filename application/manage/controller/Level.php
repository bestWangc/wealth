<?php
namespace app\manage\controller;

use think\Db;
use think\facade\Request;
use think\Exception;

class Level extends Base
{
    public function apply()
    {
        return $this->fetch();
    }

    //晋级记录
    public function record(Request $request)
    {
        $uid = $request::post('uid/d');
        if(empty($uid)){
            return jsonRes(1,'请选择用户');
        }
        $levelApply = Db::name('level_apply')
            ->alias('la')
            ->join('level l','l.id = la.level_id')
            ->where('la.uid',$uid)
            ->field('l.title,la.id,la.create_time,la.status,la.action_status')
            ->order('la.create_time desc')
            ->select();
        if(!empty($levelApply)){
            foreach ($levelApply as $k => &$v){
                $v['create_time'] = date('Y-m-d H:i:s',$v['create_time']);
                switch ($v['status']) {
                    case 1:
                        $v['status'] = '成功';
                    break;
                    case 2:
                        $v['status'] = '拒绝';
                        break;
                    default:
                        $v['status'] = '审核中';
                }
                if($v['action_status'] == 1){
                    $v['action_status'] = '已发放';
                }else{
                    $v['action_status'] = '未发放';
                }
            }
        }
        return jsonRes(0,'success',$levelApply);
    }

    //提现申请详细信息
    public function applyDetails()
    {
        $result = Db::name('level_apply')
            ->alias('la')
            ->join('users su','su.id = la.uid')
            ->join('level l','l.id = la.level_id')
            ->where('la.status',2)
            ->field('la.id,la.uid,la.create_time,su.user_name as name,l.title')
            ->order('la.create_time asc')
            ->select();
        if(!empty($result)){
            foreach ($result as $key => &$value){
                $value['create_time'] = date('Y-m-d H:i:s',$value['create_time']);
            }
        }
        return jsonRes(0,'成功',$result);
    }

    //提现申请操作
    public function operation(Request $request)
    {
        $id = $request::post('id',0);
        $operate = $request::post('operate');
        $reason = $request::post('reason','');

        if(!$id || is_null($operate)){
            return jsonRes(1,'参数不够，请重试');
        }

        Db::startTrans();
        try {
            $applyInfo = Db::name('level_apply')
                ->where('id',$id)
                ->where('status',2)
                ->find();
            $data = [
                'status' => $operate,
                'update_time' => time(),
                'remark'=> $reason
            ];
            if(!!$operate){
                $result = Db::name('users')
                    ->where('id',$applyInfo['uid'])
                    ->setField('level_id',$applyInfo['level_id']);
                if(!$result) throw new Exception('更改用户级别错误');

                //发放奖励
                $levelInfo = Db::name('level')
                    ->where('id',$applyInfo['level_id'])
                    ->find();
                $res = writeMoney($applyInfo['uid'], $levelInfo['bonus_amount'], "晋级{$levelInfo['title']}奖励", 4);
                if(!$res) throw new Exception('发放奖励错误');

                $data['action_status'] = 1;
            }

            $res = Db::name('level_apply')
                ->where('id',$id)
                ->update($data);

            if(!$res) throw new Exception('修改状态错误');
            // 提交事务
            Db::commit();
            return jsonRes(0,'成功');
        } catch (\Exception $e) {
            var_dump($e->getMessage());
            // 回滚事务
            Db::rollback();
            return jsonRes(1,'失败');
        }
    }
}
