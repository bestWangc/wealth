<?php
namespace app\index\controller;

use think\facade\Log;
use think\facade\Session;
use think\Db;

class Task
{
    public function info()
    {
        $round = mt_rand(10,100);
        $info = [
            [
                '尝试接取广告浏览任务...',
                '扫描广告浏览任务数量...',
                '接取广告浏览任务成功,任务数量；'.$round,
                '任务转为后台执行成功...',
                '-------------------------------------------'
            ],
            [
                '尝试接取广告点击任务...',
                '扫描广告点击任务数量...',
                '接取广告点击任务成功,任务数量；'.$round,
                '任务转为后台执行成功...',
                '-------------------------------------------'
            ],
            [
                '尝试接取广告点击任务...',
                '扫描广告点击任务数量...',
                '接取广告点击任务失败...',
                '-------------------------------------------'
            ],
            [
                '尝试接取广告浏览任务...',
                '扫描广告浏览任务数量...',
                '接取广告浏览任务失败...',
                '-------------------------------------------'
            ]
        ];
        $roundArr = [0,1,2,3,0,1,0,1,2,3,0,1];
        $chose = mt_rand(0,11);
        sleep(1);
        $return = $info[$roundArr[$chose]];
        return jsonRes(0,'success',$return);
    }

}
