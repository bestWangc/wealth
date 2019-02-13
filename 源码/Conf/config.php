<?php
return array(
    'URL_MODEL' => 1, //url访问模式   1-兼容模式  2-Rewrite模式
    'URL_HTML_SUFFIX' => '.html', // URL伪静态后缀设置
    'URL_PATHINFO_DEPR' => '/', // PATHINFO模式下，各参数之间的分割符号
    'URL_CASE_INSENSITIVE' => true, // URL地址是否不区分大小写


    /* 数据库设置 */
    'DB_TYPE' => 'mysql', // 数据库类型
    'DB_HOST' => 'localhost', // 服务器地址
    'DB_NAME' => 'test',  //数据库名称
    'DB_USER' => 'root', // 数据用户名
    'DB_PWD' => 'www.wazyb.com',  //数据库密码
    'DB_PORT' => 3306, // 数据库端口
    'DB_PREFIX' => 'xx_', // 数据库表前缀
    'DB_SUFFIX' => '', // 数据库表后缀
    //模板自动跳转设置
    'TMPL_ACTION_SUCCESS' => 'Public:success',
    'TMPL_ACTION_ERROR' => 'Public:error',
    
    //网站根目录绝对地址  如果放在网站根目录则设置为空
    'ROOT_PATH'=>'/lejinhui',
    //富文本编辑器文件上传路径 该路径已经默认位于/public/目录下 结尾请加/
    'EDITOR_UPLOAD_PATH'=>'upload/',
    
    
    /*缓存设置*/
    'DATA_CACHE_SUBDIR'=>false,  //是否开启子目录缓存  false-关闭
    'DATA_PATH_LEVEL'=>2,  //子目录级别
    
    'Cache_config'=>true,   //是否开启站点配置缓存  true-打开  false-关闭
    'Cache_config_time'=>0,  //站点总配置缓存时间  0-永久  修改站点配置文件会自动清空该缓存
    
);
?>