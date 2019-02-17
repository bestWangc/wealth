<?php
namespace app\manage\controller;

use think\Db;
use think\facade\Session;
use think\facade\Request;

class User extends Base
{

    public function index()
    {
        return $this->fetch();
    }

    //用户详细信息
    public function userDetails()
    {

        $useRole = Session::get('user_role');
        $where = ['parent_id' => $this->uid];
        if($useRole == 1){
            $where = '';
        }
        $userInfo = Db::name('users')
            ->where($where)
            ->field('id as uid,name,tel,email,status,created_date')
            ->order('created_date desc')
            ->select();

        if(!empty($userInfo)){
            foreach ($userInfo as $key => &$value){
                $value['created_date'] = date('Y-m-d H:i:s',$value['created_date']);
                $value['status'] = $value['status'] ? '启用' : '停用';
            }
            return jsonRes(0,'成功',$userInfo);
        }
        return jsonRes(0,'成功',$userInfo);
    }

    //修改密码
    public function changePwd()
    {
        $this->assign([
            'uid' => $this->uid,
            'uname' => Session::get('user_name')
        ]);
        return $this->fetch('changePwd');
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
