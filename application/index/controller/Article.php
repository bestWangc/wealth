<?php
namespace app\index\controller;

use think\Db;

class Article extends Base
{

    public static function getContentByID($id)
    {
        $content = Db::name("article")
            ->where("kind_id", $id)
            ->value("content");
        return $content;
    }
}
