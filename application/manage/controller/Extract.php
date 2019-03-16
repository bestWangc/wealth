<?php
namespace app\manage\controller;

use think\Db;
use think\Exception;
use think\facade\Request;

class Extract extends Base
{

    /*public function index()
    {
        return $this->fetch();
    }*/
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
    public function apply()
    {
        return $this->fetch();
    }
    //提现申请详细信息
    public function applyDetails()
    {
        $result = Db::name('extract_apply')
            ->alias('se')
            ->join('users su','su.id = se.uid')
            ->where('se.status',2)
            ->field('se.id,se.uid,su.user_name as name,se.uid,se.epoints,se.create_time,su.alipay_no,su.alipay_pic')
            ->order('se.create_time asc')
            ->select();
        if(!empty($result)){
            $fee = getConfig('cash_fee');
            foreach ($result as $key => &$value){
                $value['create_time'] = date('Y-m-d H:i:s',$value['create_time']);
                $value['real_amount'] = bcdiv($value['epoints']*(1-$fee),1,2);
            }
        }
        return jsonRes(0,'成功',$result);
    }

    //提现申请操作
    public function operation(Request $request)
    {
        $id = $request::post('id',0);
        $operate = $request::post('operate');
        $amount = $request::post('amount/d',0);
        $user_id = $request::post('user_id');
        $reason = $request::post('reason','');

        if(!$id || is_null($operate) || is_null($user_id)){
            return jsonRes(1,'参数不够，请重试');
        }

        // 启动事务
        Db::startTrans();
        try {
            $data = [
                'status' => $operate,
                'update_time' => time(),
                'remark'=> $reason,
                'update_admin_id' => $this->uid
            ];
            if(!!$operate){
                $result = Db::name('users')
                    ->dec('money',$amount)
                    ->where('id',$user_id)
                    ->update();
                if(!$result) throw new Exception('更改金额错误');
            }
            $res = Db::name('extract_apply')->where('id',$id)->update($data);

            if(!$res) throw new Exception('修改状态错误');
            // 提交事务
            Db::commit();
            return jsonRes(0,'成功');
        } catch (\Exception $e) {
            // 回滚事务
            Db::rollback();
            return jsonRes(1,'审核失败');
        }
    }
}
