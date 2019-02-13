<?php
namespace app\admins;


class Main extends Base
{

    public function main() {
        $this->sum_num = M("Users")->where(array("status" => 1))->count();

        $this->money_sum = M("Users")->where(array("status" => 1))->sum("money");
        $this->bi_sum = M("Users")->where(array("status" => 1))->sum("bi");

        $this->tixian_sum = M("TixianApply")->where(array("status" => 0))->sum("epoints");

        $this->display();
    }

    public function day_execute() {
        $this->display();
    }

    public function day_execute_action() {
        ob_end_clean();
        
        echo '开始执行：<br>';
        
        $prefix=C("DB_PREFIX");
        $now_date=  get_date();
        
        $where="status=1 and bi>0 and id not in (select uid from {$prefix}daily_execute where create_date='{$now_date}')";
        
        $list=M("Users")->field("id,user_name,bi,path")->where($where)->select();
        
        foreach ($list as $key => $value) {
            //收益币每日收益
            $fee=  round($value['bi']*MC("daily_income"),2);
            
            
            dump($value['path']);
            
            //计算推荐人的奖金
            if(!empty($value['path'])){
                $path=  str_replace("-",'',$value['path']);
                $path_array=  explode(',', $path);
                
                dump($path_array);
                
                //一级推荐人
                if(!empty($path_array[0])){
                    write_money($path_array[0], round($fee*MC('first_level'), 2), "一级推荐奖金".(MC('first_level')*100).'%', 4);
                }
                //二级推荐人
                if(!empty($path_array[1])){
                    write_money($path_array[1], round($fee*MC('second_level'), 2), "二级推荐奖金".(MC('second_level')*100).'%', 4);
                }
                //三级推荐人
                if(!empty($path_array[2])){
                    write_money($path_array[2], round($fee*MC('three_level'), 2), "三级推荐奖金".(MC('three_level')*100).'%', 4);
                }
            }
            write_money($value['id'], $fee, "每日利息", 1);
            $this->write_execute_history($value['id'], $fee);
            echo "会员id={$value['id']},用户名：{$value['user_name']}，利息{$fee}<br>";
        }
        
        echo '<font color="red">全部执行分配完毕！</font>';
    }
    
    
    /**
     * 写入当日运行记录
     * @param type $uid
     * @param float $num 当日分配利息
     */
    private function write_execute_history($uid,$num=0){
        $data=array(
            "uid"=>$uid,
            "create_date"=>get_date(),
            "create_time"=>time(),
            "epoints"=>$num
        );
        M("DailyExecute")->add($data);
    }

}