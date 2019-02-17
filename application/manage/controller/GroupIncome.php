<?php

namespace app\manage\controller;

use think\Controller;

class GroupIncome extends Controller
{
    public function index()
    {
        return $this->fetch();
    }
}
