<?php
/**
 * 短信发送记录
 *
 * @author Administrator
 */
class Smslog{
    /**
     *写入短信发送日志 
     */
    public function save_log($sms_id=0,$send_content='',$send_mobiles='',$action_message='',$return_num='0'){
        $data=array(
            'sms_id'=>$sms_id,
            'send_content'=>$send_content,
            'send_mobiles'=>$send_mobiles,
            'action_message'=>$action_message,
            'send_time'=>time(),
            'return_num'=>$return_num
        );
        $log=M('SmsSendlog');
        $log->add($data);
    }
}

?>
