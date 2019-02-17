<?php
namespace app\admins\controller;

use think\Controller;

/**
 * js全局路径解析控制器
 */
class Js extends Controller
{
    public function url()
    {
        return $this->fetch();
    }
}
