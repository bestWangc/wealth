<?php
namespace app\admins;

/**
 * 文章管理
 */
class Article extends Base
{
    public function add() {
        $ContentType = M('ContentType');
        $list = $ContentType
                ->field('id,name')
                ->order('sort')
                ->select();
        unset($ContentType);

        $this->assign('type_list', $list);

        $this->display();
    }
    
    public function edit() {
        $id = intval($_GET['id']);
        $M = M(MODULE_NAME);
        $pk = $M->getPk();


        $info = $M->where("{$pk}={$id}")->find();
        unset($M);
        $this->assign($info);

        
        
        $ContentType = M('ContentType');
        $list = $ContentType
                ->field('id,name')
                ->order('sort')
                ->select();
        unset($ContentType);

        $this->assign('type_list', $list);
        $this->display();
    }
    
}

?>
