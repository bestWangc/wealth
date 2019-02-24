<?php
namespace app\index\controller;

use think\Db;

class Level extends Base
{
    public function index()
    {
        return $this->fetch();
    }
}
