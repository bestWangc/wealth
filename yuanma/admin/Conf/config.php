<?php
$config = require("../Conf/config.php");
$array=array(
    'URL_MODEL' => 1, //url访问模式
    'URL_ROUTER_ON'=>false,
);
return array_merge($config,$array);
?>