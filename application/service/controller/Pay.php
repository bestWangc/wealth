<?php
namespace app\service\controller;

use think\Db;
use think\facade\Log;
use think\facade\Request;

class Pay extends Base
{
    public function payBack(Request $request)
    {
        $allParams = $request::get();

        if (!empty($allParams)) {
            if(array_key_exists('orderNum',$allParams) && array_key_exists('errorDetail',$allParams)){
                if($allParams['respcd'] === '00'){
                    $orderInfo = Db::name('recharge')
                        ->where('order_no',$allParams['orderNum'])
                        ->where('status',2)
                        ->find();
                    if(!empty($orderInfo)){
                        $respose = [
                            'channelOrderNum' => $allParams['channelOrderNum'],
                            'chcd' => $allParams['chcd'],
                            'consumerAccount' => $allParams['consumerAccount'],
                            'errorDetail' => $allParams['errorDetail'],
                            'orderNum' => $allParams['orderNum'],
                            'txamt' => $allParams['txamt']
                        ];
                        Db::startTrans();
                        try {
                            $uData = [
                                'status' => 1,
                                'updated_date' => time(),
                                'response' => json_encode($respose)
                            ];
                            $res = Db::name('recharge')
                                ->where('id',$orderInfo['id'])
                                ->update($uData);
                            if($res){
                                Db::name('users')
                                    ->where('id',$orderInfo['user_id'])
                                    ->setInc('money',$orderInfo['amount']);
                            }
                            // 提交事务
                            Db::commit();
                            return 'SUCCESS';
                        } catch (\Exception $e) {
                            // 回滚事务
                            Db::rollback();
                        }
                    }
                }
            }
        }

        return 'FAIL';
    }

    public function payFront(Request $request)
    {
        $allParams = $request::get();

        if (!empty($allParams)) {
            if(array_key_exists('orderNum',$allParams) && array_key_exists('errorDetail',$allParams)){
                if($allParams['errorDetail'] === 'SUCCESS'){
                    $orderInfo = Db::name('recharge')
                        ->where('order_no',$allParams['orderNum'])
                        ->where('status',2)
                        ->find();
                    if(!empty($orderInfo)){
                        $respose = [
                            'channelOrderNum' => $allParams['channelOrderNum'],
                            'chcd' => $allParams['chcd'],
                            'consumerAccount' => $allParams['consumerAccount'],
                            'errorDetail' => $allParams['errorDetail'],
                            'orderNum' => $allParams['orderNum'],
                            'txamt' => $allParams['txamt']
                        ];
                        Db::startTrans();
                        try {
                            $uData = [
                                'status' => 1,
                                'updated_date' => time(),
                                'response' => json_encode($respose)
                            ];
                            $res = Db::name('recharge')
                                ->where('id',$orderInfo['id'])
                                ->update($uData);
                            if($res){
                                Db::name('users')
                                    ->where('id',$orderInfo['user_id'])
                                    ->setInc('money',$orderInfo['amount']);
                            }
                            // 提交事务
                            Db::commit();
                            return 'SUCCESS';
                        } catch (\Exception $e) {
                            // 回滚事务
                            Db::rollback();
                        }
                    }
                }
            }
        }

        return 'FAIL';
    }
}