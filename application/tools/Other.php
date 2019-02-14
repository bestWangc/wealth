<?php
namespace app\tools;

class Other
{
    /**
     * 验证text 是不是只包含数字
     * @author wangc
     * @param $text
     * @return int
     */
    public static function checkText($text){
        $num = 0;
        if(empty($text)) $num ++;

        //大写字母
        if(preg_match("/([A-Z])+/",$text)){
            $num ++;
        }
        //小写字母
        if(preg_match('/([a-z])+/',$text)){
            $num++;
        }
        //特殊字符
        if(preg_match('/[^a-zA-Z0-9]+/',$text)){
            $num++;
        }
        return $num;
    }
}