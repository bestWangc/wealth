<?php
namespace app\manage\controller;

use think\Db;
use think\facade\Request;

class Recharge extends Base
{

    public function index(Request $request)
    {
        $choseUid = $request::param('choseUid/d',0);

        $this->assign('choseUid',$choseUid);
        return $this->fetch();
    }

    public function rechargeLog(Request $request)
    {
        $uid = $request::post('choseUid/d');
        $where = ['sr.user_id'=>$uid];

        if(!$uid){
            $where = ['su.parent_id'=>$this->uid];
        }
        $field = 'sr.id as recharge_id,su.name,sr.amount,sr.way,sr.status,sr.created_date';

        $result = Db::name('recharge')
            ->alias('sr')
            ->join('users su','su.id = sr.user_id')
            ->where($where)
            ->field($field)
            ->order('sr.created_date desc')
            ->select();
        if(!empty($result)){
            foreach ($result as $key => &$value){
                $value['status'] = statusTrans($value['status']);
                $value['created_date'] = date('Y-m-d H:i:s',$value['created_date']);
            }
        }
        return jsonRes(0,'成功',$result);
    }

    //充值页面
    public function applyFor()
    {
        return $this->fetch();
    }

    //充值申请详细数据
    public function applyDetails()
    {
        $result = Db::name('recharge')
            ->alias('sr')
            ->join('users su','su.id = sr.user_id')
            ->where('sr.status',2)
            ->field('sr.id,sr.user_id,su.name,sr.amount,sr.way,sr.status,sr.created_date')
            ->order('sr.created_date asc')
            ->select();
        if(!empty($result)){
            foreach ($result as $key => &$value){
                $value['status'] = statusTrans($value['status']);
                $value['created_date'] = date('Y-m-d H:i:s',$value['created_date']);
            }
        }
        return jsonRes(0,'成功',$result);
    }

    //操作
    public function operation(Request $request)
    {
        $id = $request::post('id',0);
        $operate = $request::post('operate');
        $amount = $request::post('amount',0);
        $user_id = $request::post('user_id');
        $reason = $request::post('reason','');

        if(!$id || is_null($operate) || is_null($user_id)){
            return jsonRes(1,'参数不够，请重试');
        }
        $data = [
            'status' => $operate,
            'updated_date' => time(),
            'refuse_reason' => $reason
        ];
        $res = Db::name('recharge')->where('id',$id)->update($data);
        if(!!$operate){
            $result = Db::name('users')
                ->inc('money',$amount)
                ->where('id',$user_id)
                ->update();
            if(!$result){
                return jsonRes(1,'充值未成功');
            }
        }
        if($res){
            return jsonRes(0,'成功');
        }
        return jsonRes(1,'失败，请重试');
    }

}
