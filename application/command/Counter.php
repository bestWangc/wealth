<?php

namespace app\command;

use think\console\Command;
use think\console\Input;
use think\console\Output;
use \simple_html_dom\simple_html_dom;
use think\Db;
use think\facade\Log;

class Counter extends Command
{
    protected function configure()
    {
        $this->setName('counter')->setDescription('统计每日平台流水信息');
    }

    protected function execute(Input $input, Output $output)
    {
        $output->writeln('开始计算利息');
        $now_date = get_date();

        //已经计算过利息的
        $hasCountId = Db::name('daily_execute')
            ->where('create_date',$now_date)
            ->column('uid');

        //未计算过利息的
        $list = Db::name('users')
            ->alias('u')
            ->join('worker w','w.user_id = u.id and w.status = 1')
            ->join('worker_type wt','wt.id = w.worker_type_id')
            ->field('u.id,u.user_name,u.path,w.id as worker_id,w.work_time,wt.daily_income,wt.retire,wt.work_time')
            ->where('u.status',1)
            ->whereNotIn('u.id',$hasCountId)
            ->select();

        if(empty($list)){
            $output->writeln('没有人需要计算利息');
            return false;
        }

        $first_level = getConfig('first_level');
        $second_level = getConfig('second_level');
        $three_level = getConfig('three_level');
        foreach ($list as $key => $value) {
            if($value['retire'] != 0){
                if($value['work_time'] > $value['retire']){
                    Db::name('worker')
                        ->where('id',$value['worker_id'])
                        ->setField('status',0);
                    continue;
                }
            }
            //收益币每日收益
            $income = $value['daily_income'];
            //计算推荐人的奖金
            if(!empty($value['path'])){
                $path_array=  explode(',', $value['path']);

                //一级推荐人
                if(!empty($path_array[0])){
                    writeMoney($path_array[0], round($income*$first_level, 2), "一级推荐奖金".($first_level*100).'%', 4);
                }
                //二级推荐人
                if(!empty($path_array[1])){
                    writeMoney($path_array[1], round($income*$second_level, 2), "二级推荐奖金".($second_level*100).'%', 4);
                }
                //三级推荐人
                if(!empty($path_array[2])){
                    writeMoney($path_array[2], round($income*$three_level, 2), "三级推荐奖金".($three_level*100).'%', 4);
                }
            }

            writeMoney($value['id'], $income, "矿工赚取收益", 1);
            Db::name('worker')
                ->where('id',$value['worker_id'])
                ->where('status',1)
                ->setInc('work_time',1);
            $this->write_execute_history($value['id'], $income);
            trace("会员id={$value['id']},用户名：{$value['user_name']}，收益{$income}/r/n");
        }
        $output->writeln('计算结束');

        return true;
    }

    /**
     * 写入当日运行记录
     * @param type $uid
     * @param float $num 当日分配利息
     */
    private function write_execute_history($uid,$num = 0){
        $data = [
            "uid" => $uid,
            "create_date"=> get_date(),
            "create_time" => time(),
            "epoints"=> $num
        ];
        Db::name("daily_execute")->insert($data);
    }
}