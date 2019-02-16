<?php
namespace app\index\controller;

use think\Controller;
use think\Exception;
use think\facade\Session;
use think\Db;

class Base extends Controller
{

    protected static $user_id;

    //网站配置
    public static $userInfo;
    public static $coinPrice;
    public static $signIncome;

    protected function initialize()
    {
        parent::initialize();
        $this->checkLogin();

        $user_id = Session::get('u_id');

        self::$user_id = $user_id;
        $siteName = MC('site_name');
        $signIncome = MC('sign_income');
        $coinPrice = MC('coin_price');
        self::$coinPrice = $coinPrice;

        $userInfo = Db::name("users")
            ->where("id", $user_id)
            ->find();
        $jibie = get_jibie($userInfo['jibie_id']);
        $userInfo['jibie_name'] = is_array($jibie) ? $jibie['title'] : $jibie;

        self::$userInfo = $userInfo;
        self::$signIncome = $signIncome;
        $this->assign([
            'user_info' => $userInfo,
            'siteName' => $siteName,
            'signIncome' => $signIncome,
            'coinPrice' => $coinPrice
        ]);
    }

    public function checkLogin(){
        //seeion没有u_id 重新登录
        if(!session('u_id')) $this->redirect('/index/login');
    }

    /**
     * 写入用户财务记录
     * @param type $uid 用户id
     * @param type $money 金额
     * @param string $text 说明
     * @param type $type 类型 0-其他 1-利息 2-提现 3-充值 4-奖励 5-签到 6-返还
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
                $res = Db::name('moneyhistory')->insert($data);

                if(!$res) throw new Exception('新建记录错误');
                // 提交事务
                Db::commit();
                return true;
            } catch (\Exception $e) {
                var_dump($e->getMessage());
                // 回滚事务
                Db::rollback();
                return false;
            }
        }
    }

}
