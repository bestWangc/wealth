<?php
require '../Common/common.php';


/**
 * 获取级别名称
 */
function get_jibie_name($jibie_id) {
    $info = get_jibie($jibie_id);
    if (is_array($info)) {
        return $info['title'];
    } else {
        return $info;
    }
}

/**
 * 获取直推人数
 * @return type
 */
function get_zhitui($uid) {
    return M("Users")->where(array("main" => $uid))->count();
}

/**
 * 获取团队人数
 */
function get_team_sum($uid) {
    $sum_num = M("Users")->where("path like '%-{$uid}-%'")->count();
    $sum_num+=1;
    return $sum_num;
}


/**
 * 获取管理员名称
 * @param type $id
 */
function get_admin_name($id){
    return M("Admin")->where(array("ly_id"=>$id))->getField("ly_name");
}


/**
 * 获取会员名称
 * @param type $id
 */
function get_user_jibie($id){
    return M("Users")->where(array("id"=>$id))->getField("jibie_id");
}

/**
 * 获取会员名称
 * @param type $id
 */
function get_user_name($id){
    return M("Users")->where(array("id"=>$id))->getField("user_name");
}

function get_user_real_name($id){
    return M("Users")->where(array("id"=>$id))->getField("real_name");
}

function get_user_alipay($id){
    return M("Users")->where(array("id"=>$id))->getField("zhifubao_no");
}

/**
 * 写入管理员充值记录
 */
function write_admin_money($uid,$num){
    $data=array(
        "uid"=>$uid,
        "epoints"=>$num,
        "create_time"=>time(),
        "admin"=>$_SESSION['ly_id']
    );
    M("AdminMoneyhistory")->add($data);
}

/**
 * 写入管理员充值收益币记录
 */
function write_admin_bi($uid,$num){
    $data=array(
        "uid"=>$uid,
        "epoints"=>$num,
        "create_time"=>time(),
        "admin"=>$_SESSION['ly_id']
    );
    M("AdminBihistory")->add($data);
}



 /**
  * 弹出提示框
  *
  * @var String $name 提示信息
  * @var String $url 返回链接
  * 
  */
 function pop($name,$url){
     header("Content-Type: text/html; charset=UTF-8");
     echo '<script language="javascript">';
     echo "alert('{$name}');";
     echo "location.href='{$url}'";
     echo '</script>';
     exit();
 }


 /**
  * 弹出提示框，并执行一个脚本
  *
  * @var String $name 提示信息
  * @var String 其他脚本
  *
  */
 function pop_js($name,$js){
     header("Content-Type: text/html; charset=UTF-8");
     echo '<script language="javascript">';
     echo "alert('{$name}');";
     echo "{$js}";
     echo '</script>';
     exit();
 }

  /**
  * 弹出提示框，上级框架跳转
  *
  * @var String $name 提示信息
  * @var String $url 返回链接
  *
  */
 function pop_parent($name,$url){
     header("Content-Type: text/html; charset=UTF-8");
     echo '<script language="javascript">';
     echo "alert('{$name}');";
     echo "parent.location.href='{$url}'";
     echo '</script>';
     exit();
 }


 /**
  * 管理员登录状态检测
  */
 function check_admin(){
     if($_SESSION['ly_id']==''||$_SESSION['ly_name']==''){
         pop('您尚未登录系统，请正确登录系统！',U("index/index"));
         exit;
     }
 }
 
 
    /**
    * 输出系统处理中，请稍候的信息
    */
    function print_wait(){
        header("Content-Type: text/html; charset=UTF-8");
        echo '<img src="'.__ROOT__.'/images/loading.gif" /><font size="2">系统处理中...请稍后！</font>';
    }

?>
