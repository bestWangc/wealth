<?php
namespace app\index;

use think\Controller;


class Base extends Controller {

    public function _initialize() {
        $user_id = session("user_id");

        if (empty($user_id)) {
            $this->error("请正确登录系统！", U("/"));
        }

        $this->user_id = $user_id;

        $user_info = M("Users")->where(array("id" => $user_id))->find();

        $jibie = get_jibie($user_info['jibie_id']);

        $user_info['jibie_name'] = is_array($jibie) ? $jibie['title'] : $jibie;

        $this->user_info = $user_info;
    }

}
