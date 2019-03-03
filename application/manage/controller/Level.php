<?php
namespace app\manage\controller;

use think\Db;
use think\facade\Request;

class Level extends Base
{
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
}
