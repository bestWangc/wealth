<?php

/**
 * 会员管理
 *
 * @@CreateTime 2016-8-4 14:32:16
 * @version v1.0
 */
class UsersAction extends CommonAction {

    public function index() {
        $user_name=$_GET['user_name'];
        
        
        $this->user_name=$user_name;
        
        if(empty($user_name)){
            $where="";
        }else{
            $where=array(
                "user_name"=>array("like","%{$user_name}%")
            );
        }
        
        
        $M = D(MODULE_NAME);
        import("ORG.Util.Page");
        $count = $M->where($where)->count('id');
        $Page = new Page($count, 50);
        $show = $Page->show();
        $this->assign('page', $show);

        $list = $M->where($where)->order($sort)->limit($Page->firstRow . ',' . $Page->listRows)->select();
        unset($Page);
        $this->assign('list', $list);
        $this->display();
    }

    public function edit() {
        $this->jibie_list = M("Jibie")->order("sort,id desc")->select();
        parent::edit();
    }

    public function update() {
        $D = D(MODULE_NAME);
        $data = $_POST;
        $data['update_time'] = time();

        if (empty($_POST['user_pwd'])) {
            unset($data['user_pwd']);
        } else {
            $data['user_pwd'] = md5($data['user_pwd']);
        }


        // 更新数据
        $list = $D->save($data);
        unset($D);
        if (false !== $list) {
            $this->assign('jumpUrl', U(MODULE_NAME . '/index'));
            $this->success('修改成功！');
        } else {
            $this->error('修改失败！');
        }
    }

}

