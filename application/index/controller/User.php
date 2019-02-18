<?php
namespace app\index\controller;

use think\Db;
use think\facade\Session;
use think\facade\Request;

class User extends Base
{

    public function index()
    {
        $role_name = Index::getUserRole($this::$uid);
        $this->assign([
            'userInfo' =>$this::$userInfo,
            'roleName' => $role_name
        ]);

        return $this->fetch();
    }

    //修改密码
    public function changePwd()
    {
        $this->assign([
            'uid' => $this::$uid,
            'uname' => Session::get('user_name')
        ]);
        return $this->fetch('changePwd');
    }

    public function doChangePwd(Request $request)
    {
        $type = $request::post('type/d');

        if(empty($type)){
            return jsonRes(1,'未知错误，请重试');
        }
        $oldPwd = $request::param('oldPwd', "");
        $newPwd = $request::param('newPwd', "");
        $reNewPwd = $request::param('reNewPwd', "");
        if($newPwd != $reNewPwd){
            return jsonRes(1,'两次新密码不一致');
        }

        $userInfo = $this::$userInfo;
        $field = '';
        switch ($type) {
            case 1:  //登录密码
                $sysOldPwd = $userInfo['user_pwd'];
                $field = "user_pwd";
                break;
            case 2: //二次密码
                $sysOldPwd = $userInfo['user_pwd1'];
                $field = "user_pwd1";
                break;
        }

        if($sysOldPwd != md5($oldPwd.'jstj')){
            return jsonRes(1,'原密码不正确');
        }

        $data = [
            $field => md5($newPwd.'jstj')
        ];

        $res = Db::name("users")
            ->lock()
            ->where("id", $this::$uid)
            ->update($data);
        if($res){
            return jsonRes(0,'密码修改成功，请牢记您的新密码');
        }
        return jsonRes(1,'密码修改失败，请稍后再试');
    }


    // 保存用户信息
    public function saveUserInfo(Request $request)
    {
        $tel = $request::post('tel');
        $pattern = '/^1[34578]\d{9}$/';
        if(!preg_match($pattern,$tel)){
            return jsonRes(1,'请填写正确的手机号');
        }

        $alipayAccount = $request::post('alipayAccount');
        $realName = $request::post('realName');
        $alipayPic = $request::file('alipayPic');

        if(empty($realName)){
            return jsonRes(1,'请填写真实姓名');
        }
        if(empty($alipayAccount)){
            return jsonRes(1,'请填写支付宝收款帐号');
        }

        if(is_null($alipayPic)){
            $alipayPic = $request::post('alipayPic');
            if(empty($alipayPic)){
                return jsonRes(1,'请上传支付宝收款二维码');
            }
        }

        Db::startTrans();
        try {
            if(gettype($alipayPic) != 'string'){
                $upload = uploadPic($alipayPic,$this::$uid.'alipay');
            }else{
                $upload = $alipayPic;
            }

            if(!empty($upload)){
                $data = [
                    'real_name' => $realName,
                    'mobile' => $tel,
                    'alipay_no' => $alipayAccount,
                    'alipay_pic' => $upload
                ];

                Db::name('users')->where('id',$this::$uid)->update($data);
            }else{
                throw new Exception('error');
            }
            unset($data);
            // 提交事务
            Db::commit();

            return jsonRes(0,'保存成功');
        } catch (\Exception $e) {
            // 回滚事务
            Db::rollback();
            return jsonRes(1,'错误，请重试');
        }
    }

    //抢购记录
    public function buyLog(Request $request)
    {
        $choseUid = $request::param('choseUid/d',0);

        $this->assign('choseUid',$choseUid);
        return $this->fetch();
    }

    //抢购详细记录
    public function buyLogDetails(Request $request)
    {
        $uid = $request::post('choseUid/d');
        $where = ['so.user_id'=>$uid];
        if(!$uid){
            $where = ['su.parent_id'=>$this->uid];
        }

        $result = Db::name('order')
            ->alias('so')
            ->join('users su','su.id = so.user_id','left')
            ->join('goods sg','sg.id = so.goods_id','left')
            ->join('award_info sai','sai.id = so.award_id','left')
            ->where($where)
            ->field('so.id as order_id,su.`name`,sg.`name` as good_name,so.goods_num,so.amount,so.guessing,sai.term_num,so.created_date')
            ->order('so.created_date desc')
            ->select();

        if(!empty($result)){
            foreach ($result as $key => &$value){
                $value['guessing'] = $value['guessing'] ? '丰年' : '瑞雪';
                $value['created_date'] = date('Y-m-d H:i:s',$value['created_date']);
            }
        }
        return jsonRes(0,'成功',$result);
    }

}
