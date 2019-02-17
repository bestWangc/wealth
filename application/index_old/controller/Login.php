<?php
namespace app\index_old\controller;
use think\Controller;
use think\Db;
use think\facade\Request;
use think\facade\Session;

class Login extends Controller
{

    public function index()
    {
        $siteName = MC('site_name');
        $this->assign('siteName',$siteName);
        return $this->fetch();
    }

    /**
     * 注册
     * @param Request $request
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function register(Request $request)
    {
        $uid = intval($request::param('uuu989uid'));
        $pName = '';
        $pId = 0;
        if (!empty($uid)) {
            $info = Db::name("users")
                ->where('id',$uid)
                ->field('id,user_name')
                ->find();
            $pName = $info['user_name'];
            $pId = $info['id'];
        }
        $siteName = MC('site_name');
        $this->assign([
            'siteName' => $siteName,
            'pName' => $pName,
            'pId' => $pId
        ]);

        return $this->fetch();
    }

    /**
     * 注册操作
     */
    public function reg_action()
    {
        $tuijian_switch = MC("tuijian_switch");

        $tj_uid = $_POST['tj_name'];

        $where = array(
            "status" => 1,
            "user_name" => $tj_uid
        );
        $tuijian_info = Db::name("Users")->where($where)->find();


        if ($tuijian_switch) {
            if (empty($tuijian_info)) {
                $this->error('推荐人不存在！');
            }

            if ($tuijian_info['mobile'] != $_POST['tj_mobile']) {
                $this->error('推荐人手机号码错误！');
            }
        }

        if (empty($_POST['user_name'])) {
            $this->error('请填写用户名！');
        }

        if (Db::name("Users")->where(array("user_name" => $_POST['user_name']))->count() > 0) {
            $this->error('该用户名已经被注册，请重新填写用户名！');
        }

        if (empty($_POST['mobile'])) {
            $this->error('请填写手机号码！');
        }

        if (Db::name("Users")->where(array("mobile" => $_POST['mobile']))->count() > 0) {
            $this->error('该手机号码已经被注册，请重新填写手机号码！');
        }

        if (empty($_POST['user_pwd'])) {
            $this->error('请填写登录密码！');
        }

        if (empty($_POST['user_pwd_1'])) {
            $this->error('请填写登录密码的确认密码！');
        }

        if ($_POST['user_pwd'] != $_POST['user_pwd_1']) {
            $this->error('登录密码两次输入的不一致！');
        }

        if (empty($_POST['user_pwd1'])) {
            $this->error('请填写二级密码！');
        }
        if (empty($_POST['user_pwd1_1'])) {
            $this->error('请填写二级密码确认密码！');
        }

        if ($_POST['user_pwd1'] != $_POST['user_pwd1_1']) {
            $this->error('二次密码两次输入的不一致！');
        }


        $path = empty($tuijian_info['id']) ? "" : "-{$tuijian_info['id']}-";
        if (!empty($tj_uid) && !empty($tuijian_info['path'])) {
            $path.=',' . $tuijian_info['path'];
        }

        $data = array(
            "user_name" => $_POST['user_name'],
            "user_pwd" => md5($_POST['user_pwd']),
            "user_pwd1" => md5($_POST['user_pwd1']),
            "mobile" => $_POST['mobile'],
            "main" => $tuijian_info['id']? : 0,
            "path" => $path,
            "create_time" => time(),
            "last_login_time" => time(),
            "update_time" => time(),
            "create_ip" => get_client_ip(),
            "login_count" => 1,
            "last_login_ip" => get_client_ip()
        );

        $uid = Db::name("Users")->add($data);

        Session::get("user_id", $uid);

        $this->success("注册成功！", U("/main"));
    }


    public function out()
    {
        session("user_id", NULL);
        $this->success("退出成功!", url("/"));
    }

}
