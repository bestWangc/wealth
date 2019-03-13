<?php
namespace app\manage\controller;

use think\Db;
use think\facade\Request;

class Extract extends Base
{

    public function index(Request $request)
    {
        $choseUid = $request::param('choseUid/d',0);

        $this->assign('choseUid',$choseUid);
        return $this->fetch();
    }
    //提现记录
    public function record(Request $request)
    {
        $uid = $request::post('uid/d');

        if(empty($uid)) return jsonRes(1,'请选择用户');

        $result = Db::name('extract_apply')
            ->where('uid',$uid)
            ->field('uid,epoints,create_time,status')
            ->order('create_time desc')
            ->select();

        if(!empty($result)){
            foreach ($result as $key => &$value){
                $value['status'] = $value['status'] == 1 ? '审核成功' : '审核中';
                $value['create_time'] = date('Y-m-d H:i:s',$value['create_time']);
            }
        }
        return jsonRes(0,'成功',$result);
    }

    //提现申请
    public function applyFor()
    {
        return $this->fetch();
    }
    //提现申请详细信息
    public function applyDetails()
    {
        $result = Db::name('extract')
            ->alias('se')
            ->join('users su','su.id = se.user_id')
            ->join('alipay sa','sa.id = se.alipay_id')
            ->where('se.status',2)
            ->field('se.id,su.name,se.user_id,se.amount,se.real_amount,se.way,se.status,se.created_date,sa.alipay_account,sa.alipay_name,sa.alipay_pic')
            ->order('se.created_date asc')
            ->select();
        if(!empty($result)){
            foreach ($result as $key => &$value){
                $value['status'] = statusTrans($value['status']);
                $value['created_date'] = date('Y-m-d H:i:s',$value['created_date']);
            }
        }
        return jsonRes(0,'成功',$result);
    }

    //提现申请操作
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
            'refuse_reason'=>$reason
        ];

        if(!!$operate){
            $result = Db::name('users')
                ->dec('money',$amount)
                ->dec('frozen_money',$amount)
                ->where('id',$user_id)
                ->update();
            if(!$result){
                return jsonRes(1,'未成功');
            }
        }
        $res = Db::name('extract')->where('id',$id)->update($data);
        if($res){
            return jsonRes(0,'成功');
        }
        return jsonRes(1,'失败，请重试');
    }
}
