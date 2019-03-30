<?php
namespace app\index\controller;

use think\Db;
use think\facade\Request;

class Worker extends Base
{
    public static function getWorkerInfoByUID($uid)
    {
        $info = Db::name('worker')
            ->alias('w')
            ->join('worker_type wt','wt.id = w.worker_type_id')
            ->field('w.work_time,wt.name,GROUP_CONCAT(wt.id) as worker_num,wt.id')
            ->where('user_id',$uid)
            ->group('wt.id')
            ->select();
        if(!empty($info)){
            foreach ($info as $k => &$v) {
                $numArr = explode(',',$v['worker_num']);
                $v['worker_num'] = count($numArr);
            }
        }
        return $info;
    }

    public static function getWorkerTypeInfo($id = null)
    {
        $where = [];
        if(!is_null($id)){
            $where['id'] = $id;
        }
        $info = Db::name('worker_type')
            ->field('id,name,price,daily_income,retire,work_time')
            ->where($where)
            ->select();
        return $info;
    }
}
