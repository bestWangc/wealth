<?php

/**
 * 控制器基类 
 * 包括公有常用的公有方法
 */
class CommonAction extends Action {

    function _initialize() {  //初始化
        $this->assign('module_name', parse_name(MODULE_NAME));
        $this->assign('action_name', parse_name(ACTION_NAME));
        check_admin();
    }

    public function index($num=20,$sort='sort,id desc') {
        $M = D(MODULE_NAME);
        import("ORG.Util.Page");
        $count = $M->count('id');
        $Page = new Page($count, $num);
        $show = $Page->show();
        $this->assign('page', $show);
        
        
        $list = $M->order($sort)->limit($Page->firstRow.','.$Page->listRows)->select();
        unset($Page);
        $this->assign('list', $list);
        $this->display();
    }

    public function edit() {
        $id = intval($_GET['id']);
        $M = M(MODULE_NAME);
        $pk = $M->getPk();

        $info = $M->where("{$pk}={$id}")->find();
        unset($M);
        $this->assign($info);

        $this->display();
    }

    public function update() {
        $D = D(MODULE_NAME);

        
		
		$data=$_POST;
		$data['input_time']=time();
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

    public function insert() {
        $D = D(MODULE_NAME);

        $data=$_POST;
        $data['input_time']=time();
        // 插入
        $list = $D->add($data);
        unset($D);
        if (false !== $list) {
            $this->assign('jumpUrl', U(MODULE_NAME . '/index'));
            $this->success('添加成功！');
        } else {
            $this->error('添加失败！');
        }
    }

    public function delete() {
        $D = D(MODULE_NAME);
        $id = intval($_REQUEST['id']);
        $pk = $D->getPk();
        $return = $D->where("{$pk}={$id}")->delete();
        unset($D);
        if ($return) {
            $this->ajaxReturn(1,$id);
        } else {
            $this->ajaxReturn(0);
        }
    }

}

?>
