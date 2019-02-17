<?php
namespace app\manage\controller;

use think\Db;
use think\facade\Request;

class Income extends Base
{
    public function index()
    {
        return $this->fetch();
    }

    //获取平台收益图表数据
    public function getDetailChartsInfo(Request $request)
    {
        $choseDate = $request::post('choseDate');
        if(empty($choseDate)){
            //本月1号
            $beginDate = mktime(0,0,0,date('m'),1,date('Y'));
        }else{
            if($choseDate == 6){
                $beginDate = date("Y-m-d", strtotime("-6 month"));
            } elseif ($choseDate == 12){
                $beginDate = date("Y-m-d", strtotime("-1 year"));
            }
        }

        $result = Db::name('group_income')
            ->where('created_date','>',$beginDate)
            ->field('income,`out`,(income-bonus-`out`) as net_income,created_date')
            ->order('created_date asc')
            ->select();
        $finalData = $xAxisData = [];
        if(!empty($result)){
            $finalData['income'] = array_column($result,'income');
            $finalData['out'] = array_column($result,'out');
            $finalData['out'] = array_map(function ($item) {
                return $item < 0 ? $item : -$item;
            }, $finalData['out']);
            $finalData['netIncome'] = array_column($result,'net_income');
            $finalData['netIncome'] = array_map(function ($item) {
                return bcdiv($item,1,2);
            }, $finalData['netIncome']);
            $xAxisData = array_column($result,'created_date');
            foreach ($xAxisData as &$key){
                $key = date('m/d',$key);
            }
            $finalData['xAxisData'] = $xAxisData;
        }
        return jsonRes(0,'成功',$finalData);
    }
    //获取首页图表数据
    public function getChartsInfo()
    {

        //本月1号
        $monFirst = mktime(0,0,0,date('m'),1,date('Y'));
        $result = Db::name('group_income')
            ->where('user_id',$this->uid)
            ->where('created_date','>',$monFirst)
            ->field('bonus,created_date')
            ->order('created_date asc')
            ->select();
        $finalData = [];
        if(!empty($result)){
            $resultLen = count($result);
            foreach ($result as $key => $value){
                $finalData['countAll'][] = $value['bonus'];
                $finalData['xAxisData'][] = date('d',$value['created_date']);
                if($key == $resultLen-1){
                    $finalData['yesBonus'] = $value['bonus'];
                }
            }
        }
        if(!empty($finalData)){
            $finalData['monthBonus'] = array_sum($finalData['countAll']);
        }
        return jsonRes(0,'成功',$finalData);
    }

    //结算分红
    public function settleBonus(){

    }
}
