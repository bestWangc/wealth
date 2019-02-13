<?php

/**
 * 文章管理
 *
 * @lanfengye <zibin_5257@163.com>
 */
class ArticleAction extends CommonAction {
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
