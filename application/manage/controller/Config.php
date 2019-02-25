<?php

namespace app\manage\controller;

use think\Controller;
use think\Db;
use think\facade\Request;

class Config extends Controller
{
    public function index()
    {
        $configInfo = Db::name('config')
            ->field('name,val')
            ->select();
        $config = [];
        foreach ($configInfo as $k => $v){
            $config[$v['name']] = $v['val'];
        }
        $this->assign('info',$config);
        return $this->fetch();
    }

    public function saveConfig(Request $request)
    {
        $data = $request::post('data/a');
        if(empty($data)){
            return jsonRes(1,'配置不能为空');
        }

        foreach ($data as $k => $v){
             Db::name('config')
                ->where('name',$v['name'])
                ->update(['val'=>$v['value']]);
        }

        return jsonRes(0,'保存成功');
    }
}
