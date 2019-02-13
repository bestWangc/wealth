<?php

/**
 * 系统缓存管理类
 *
 * @lanfengye <zibin_5257@163.com>
 */
class CacheAction extends CommonAction {

    /**
     * 缓存清理方法 
     */
    public function clean_cache() {
        $id = intval($_REQUEST['id']);
        switch ($id) {
            case 1:  //模板缓存
                $dir_url[] = '../Runtime/Cache';
                $dir_url[] = '../Runtime/admin_cache';
                break;
            case 2:  //数据缓存
                $dir_url[] = '../Runtime/Temp';
                break;
            case 3:  //数据库字段缓存
                $dir_url[] = '../Runtime/Data';
                break;
            case 4:  //全部缓存
                $dir_url[] = '../Runtime';
                break;
            default:  //全部缓存
                $dir_url[] = '../Runtime';
                break;
        }
        
        foreach ($dir_url as $value) {
            rmdirr($value);
            @mkdir($value, 0777, true);
        }

        $this->ajaxReturn(1);
    }

}

?>
