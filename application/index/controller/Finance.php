<?php
namespace app\index\controller;

use think\Db;
use think\facade\Request;

class Finance extends Base
{
    public function index()
    {
        return $this->fetch();
    }

    public function recordList(Request $request)
    {
        $listValue = $request::post('listValue',3);

        switch ($listValue) {
            case 1: //收入
                $where = [
                    ['uid','=',$this::$uid],
                    ['epoints', '>=', 0]
                ];
                break;
            case 2: //支出
                $where = [
                    ['uid','=',$this::$uid],
                    ['epoints', '<', 0]
                ];
                break;
            default: //全部
                $where = [
                    "uid" => $this::$uid,
                ];
                break;
        }

        $list = Db::name("money_history")
            ->where($where)
            ->order("id desc")
            ->select();
        if(!empty($list)){
            foreach ($list as $key => &$value){
                $value['create_time'] = date('Y-m-d H:i',$value['create_time']);
            }
        }

        return jsonRes(0,'success',$list);
    }
}
