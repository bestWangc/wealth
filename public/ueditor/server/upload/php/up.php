<?php
    //获取远程的配置文件内容
    $base_config=require '../../../../../Conf/config.php';
    date_default_timezone_set('UTC');
    //上传配置
    $config = array(
        //"uploadPath"=>"./temp/uploadfiles/",                       //保存路径
        "uploadPath"=>'../../../../'.$base_config['EDITOR_UPLOAD_PATH'],
        "fileType"=>array(".gif",".png",".jpg",".jpeg",".bmp"),   //文件允许格式
        "fileSize"=>5000                                   //文件大小限制，单位KB
    );
    
    
    //文件上传状态,当成功时返回SUCCESS，其余值将直接返回对应字符窜并显示在图片预览框，同时可以在前端页面通过回调函数获取对应字符窜
    $state = "SUCCESS";

    $title = htmlspecialchars($_POST['pictitle'], ENT_QUOTES);
    $path  = $config['uploadPath'];
    if(!file_exists($path)){
        mkdir("$path", 0777,true);
    }
    
    if(!file_exists($path.date('Ymd').'/')){
        mkdir($path.date('Ymd').'/', 0777,true);
    }
    
    
    //格式验证
    $current_type = strtolower(strrchr($_FILES["picdata"]["name"], '.'));
    if(!in_array($current_type, $config['fileType'])){
        $state = "不支持的图片类型！";
    }
    //大小验证
    $file_size = 1024 * $config['fileSize'];
    if( $_FILES["picdata"]["size"] > $file_size ){
        $state = "图片大小超出限制！";
    }
    //保存图片
    if($state == "SUCCESS"){
        $tmp_file=$_FILES["picdata"]["name"];
        $rand_name=rand(1,10000).time();
        $file_name=date('Ymd').'/'.$rand_name.strrchr($tmp_file,'.');
        
        $file_name1=date('Ymd').'/'.$rand_name.strrchr($tmp_file,'.');
        
        $file = $path.$file_name1;
        $result = move_uploaded_file($_FILES["picdata"]["tmp_name"],$file);
        if(!$result){
            $state = "图片保存失败！"; 
        }
    }
    
    echo "{'url':'".$file_name."','title':'".$title."','state':'".$state."'}";

?>

