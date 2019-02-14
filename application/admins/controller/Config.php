<?php
namespace app\admins\controller;

/**
 * 站点配置
 *
 * @author Administrator
 */
class Config extends Base
{

    public function index() {
        $M = M(MODULE_NAME);
        $this->assign('list', $M->where("is_show=1")->order('sort')->select());
        $this->display();
    }

    public function update() {
        $D = D(MODULE_NAME);
        $list = $D->select();
        foreach ($list as $k => $v) {
            $v['val'] = isset($_REQUEST[$v['name']]) ? $_REQUEST[$v['name']] : $v['val'];
            $D->save($v);
        }
        unset($D);
        S('config_cache',NULL);
        $this->assign('jumpUrl', U(MODULE_NAME . '/index'));
        $this->success('修改成功！');
    }

}