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

    public function record(Request $request)
    {
        $uid = $request::post('uid/d');
        if(empty($uid)) return jsonRes(1,'请选择用户');

        $result = Db::name('recharge')
            ->where('user_id',$uid)
            ->field('user_id as uid,amount,way,status,created_date')
            ->order('created_date desc')
            ->select();
        if(!empty($result)){
            foreach ($result as $key => &$value){
                switch ($value['status']){
                    case 1:
                        $status = '成功';
                        break;
                    case 0:
                        $status = '失败';
                        break;
                    default:
                        $status = '未付款';
                }
                $value['status'] = $status;
                $value['way'] = $value['way'] == 1 ? '支付宝' : '微信';
                $value['created_date'] = date('Y-m-d H:i:s',$value['created_date']);
            }
        }
        return jsonRes(0,'成功',$result);
    }
}
