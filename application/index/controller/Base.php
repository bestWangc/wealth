<?php
namespace app\index\controller;

use think\Controller;
use think\Db;

class Base extends Controller
{
    protected static $uid;
    protected static $userInfo;
    protected function initialize()
    {
        parent::initialize();
        $this->checkLogin();
        self::$uid = session('u_id');
        self::$userInfo = $this->getUserInfo(self::$uid);
    }

    public function checkLogin(){
        //seeion没有user_id 重新登录
        if(!session('u_id')) $this->redirect('/index/login');
    }

    public function getUserInfo($uid)
    {
        $res = $userInfo = Db::name("users")
            ->where("id", $uid)
            ->find();
        return $res;
    }

    /**
     * 写入用户财务记录
     * @param $uid
     * @param $money 金额
     * @param string $text 说明
     * @param int $type 类型 0-其他 1-利息 2-提现 3-充值 4-奖励 5-签到 6-返还
     * @return bool
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function writeMoney($uid, $money, $text = "", $type = 0) {

        $user_info = Db::name('users')->where("id", $uid)->find();

        if ($user_info) {
            $old_money = $user_info['money'];
            $new_money = $old_money - $money;

            Db::startTrans();
            try {
                if($type == 0 || $type == 2){
                    $res = Db::name('users')
                        ->where('id',$uid)
                        ->setDec('money',$money);
                }else{
                    $res = Db::name('users')
                        ->where('id',$uid)
                        ->setInc('money',$money);
                }

                if(!$res) throw new Exception('余额错误');

                $data = [
                    "uid" => $uid,
                    "action_type" => $type,
                    "create_time" => time(),
                    "epoints" => $money,
                    "original" => $old_money,
                    "after" => $new_money,
                    "text" => $text
                ];
                $res = Db::name('money_history')->insert($data);

                if(!$res) throw new Exception('新建记录错误');
                // 提交事务
                Db::commit();
                return true;
            } catch (\Exception $e) {
                // 回滚事务
                Db::rollback();
                return false;
            }
        }
    }
}