<?php
/**
 * 畅友网络短信接口类
 * http://www.chanyoo.cn
 *
 * @lanfengye <zibin_5257@163.com>
 * @build 2012-03-27
 */
class Sms{
    /**
     * 短信发送接口地址：
     * http://api.chanyoo.cn/utf8/interface/send_sms.aspx
     * 
     * 
     * 
     * 
     * 
     *用户信息接口地址：http://api.chanyoo.cn/utf8/interface/user_info.aspx?username=lanfengye&password=768815
     * 用户信息返回字段：
     * result=资源返回值 错误代码
     * user_balance=余额
     * user_amount=消费金额
     * sms_left=剩余短信数量
     * sms_send=发送短信数量
     * sms_receive=接收短信数量
     * expired_date=账户过期时间
     */
    
    var $sms_id=0;  //短信接口ID
    var $user_name='';   //短信接口用户名
    var $user_pwd='';  //短信接口密码
    var $server_url='';  //短信接口发送地址
    var $info_url='';  //短信接口获取用户信息地址
    
    /**
     *接口初始化方法 
     */
    function __construct(){
        //获取当前对象名称
        $class_name=get_class($this);
        //去掉类名中的Model
        $class_name=  str_replace('Model', '', $class_name);
        $info=M('Sms')->where("class_name='SmsChanyoo'")->find();
        
        $this->sms_id=$info['id'];
        $this->user_name=$info['user_name'];
        $this->user_pwd=$info['password'];
        $this->server_url=$info['server_url'];
        $this->info_url=$info['info_url'];
    }
    
    /**
     *短信发送方法
     * @param type $mobile
     * @param type $content
     * @param type $time
     * @return string 
     */
    public function send($mobile, $content, $time = '') {
        $http = $this->server_url;
        $data = array
            (
            'username' => $this->user_name, //平台账号
            'password' => $this->user_pwd, //账号密码
            'receiver' => $mobile, //号码
            'content' => $content, //内容
            'sendtime' => $time    //定时发送
        );
        $result = $this->postSMS($http, $data);  //POST方式提交

        $xml = simplexml_load_string($result);
        
        
        
        //进行发送日志的写入
        import('ORG.Sms.SmsSendlog');
        $log=new Smslog();
        $log->save_log($this->sms_id,$content,$mobile,(String)$xml->message,(String)$xml->result);
       
        
        if ($xml->result >= 0) {
            return "发送成功!";
        } else {
            return "发送失败! 状态：" . $xml->message;
        }
    }
    
    public function userinfo(){
        $http = $this->info_url;
        $data = array
            (
            'username' => $this->user_name, //平台账号
            'password' => $this->user_pwd, //账号密码
        );
        $result = $this->get_userinfo($http, $data);  //POST方式提交

        $xml = simplexml_load_string($result);

        if ($xml->result >= 0) {
            return "账户信息 余额：{$xml->user_balance}，消费金额：{$xml->user_amount}，剩余短信：{$xml->sms_left}，已发短信数量：{$xml->sms_send}，接收短信数量：{$xml->sms_receive}，账户过期日期：{$xml->expired_date}";
        } else {
            return "获取失败! 状态：" . $xml->message;
        }
    }
    
    
    
    /**
     *POST发送方法
     * @param string 发送的url
     * @param array 发送的数据
     * @return 服务器返回状态 
     */
    private function postSMS($url, $data = '') {
        $row = parse_url($url);
        $host = $row['host'];
        $port = $row['port'] ? $row['port'] : 80;
        $file = $row['path'];
        while (list($k, $v) = each($data)) {
            $post .= rawurlencode($k) . "=" . rawurlencode($v) . "&"; //转URL标准码
        }
        $post = substr($post, 0, -1);
        $len = strlen($post);
        $fp = @fsockopen($host, $port, $errno, $errstr, 10);
        if (!$fp) {
            return "$errstr ($errno)\n";
        } else {
            $receive = '';
            $out = "POST $file HTTP/1.1\r\n";
            $out .= "Host: $host\r\n";
            $out .= "Content-type: application/x-www-form-urlencoded\r\n";
            $out .= "Connection: Close\r\n";
            $out .= "Content-Length: $len\r\n\r\n";
            $out .= $post;
            fwrite($fp, $out);
            while (!feof($fp)) {
                $receive .= fgets($fp, 128);
            }
            fclose($fp);
            $receive = explode("\r\n\r\n", $receive);
            unset($receive[0]);
            return implode("", $receive);
        }
    }
    
    
    /**
     *获取用户信息方法
     * @param string 发送的url
     * @param array 发送的数据
     * @return 服务器返回状态 
     */
    private function get_userinfo($url, $data = '') {
        $row = parse_url($url);
        $host = $row['host'];
        $port = $row['port'] ? $row['port'] : 80;
        $file = $row['path'];
        while (list($k, $v) = each($data)) {
            $post .= rawurlencode($k) . "=" . rawurlencode($v) . "&"; //转URL标准码
        }
        $post = substr($post, 0, -1);
        $len = strlen($post);
        $fp = @fsockopen($host, $port, $errno, $errstr, 10);
        if (!$fp) {
            return "$errstr ($errno)\n";
        } else {
            $receive = '';
            $out = "POST $file HTTP/1.1\r\n";
            $out .= "Host: $host\r\n";
            $out .= "Content-type: application/x-www-form-urlencoded\r\n";
            $out .= "Connection: Close\r\n";
            $out .= "Content-Length: $len\r\n\r\n";
            $out .= $post;
            fwrite($fp, $out);
            while (!feof($fp)) {
                $receive .= fgets($fp, 128);
            }
            fclose($fp);
            $receive = explode("\r\n\r\n", $receive);
            unset($receive[0]);
            return implode("", $receive);
        }
    }
}

?>
