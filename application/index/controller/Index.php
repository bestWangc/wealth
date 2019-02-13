<?php
namespace app\index;

use think\Controller;

class Index extends Controller
{

    public function index()
    {
        $this->display();
    }

    /**
     * 注册界面
     */
    public function reg() {
        $uid = intval($_GET['uuu989uid']);

        if (!empty($uid)) {
            $info = M("Users")->where("id={$uid}")->find();
            $this->mobile = $info['mobile'];
            $this->user_name = $info['user_name'];
            $this->uid = $uid;
        }

        $this->display();
    }

    /**
     * 注册操作
     */
    public function reg_action() {
        $tuijian_switch = MC("tuijian_switch");

        $tj_uid = $_POST['tj_name'];


        $where = array(
            "status" => 1,
            "user_name" => $tj_uid
        );
        $tuijian_info = M("Users")->where($where)->find();


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

        if (M("Users")->where(array("user_name" => $_POST['user_name']))->count() > 0) {
            $this->error('该用户名已经被注册，请重新填写用户名！');
        }


        if (empty($_POST['mobile'])) {
            $this->error('请填写手机号码！');
        }

        if (M("Users")->where(array("mobile" => $_POST['mobile']))->count() > 0) {
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

        $uid = M("Users")->add($data);

        session("user_id", $uid);

        $this->success("注册成功！", U("/main"));
    }

    /**
     * 登录逻辑
     */
    public function check_login() {
        $user_name = $_POST['user_name'];
        $user_pwd = $_POST['user_pwd'];

        if ($user_name == '') {
            $this->error("请填写用户名！");
        }

        if ($user_pwd == '') {
            $this->error('请填写登录密码！');
        }


        $user_pwd = md5($user_pwd);

        $user_info = M("Users")->where(array("user_name" => $user_name, "status" => 1))->find();

        if (empty($user_info)) {
            $this->error('用户不存在或者被系统禁用！请重新登录！');
        }



        if ($user_info['user_pwd'] != $user_pwd) {
            $this->error('登录密码错误！请重新登录！');
        }

        session("user_id", $user_info['id']);

        $data = array(
            "last_login_time" => time(),
            "last_login_ip" => get_client_ip(),
            "login_count" => $user_info['login_count'] + 1,
        );
        M("Users")->where(array("id" => $user_info['id']))->save($data);


        $this->success("登录成功！", U("/main"));
    }

    public function out() {
        session("user_id", NULL);
        $this->success("退出成功!", U("/"));
    }

}
