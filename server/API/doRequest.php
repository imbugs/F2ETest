<?php
include('./config.php');


//浏览器类型，如果不指定将使用HtmlUnit模式
$type = isset($_REQUEST['type']) ? $_REQUEST['type'] :  'HtmlUnit';
if(!in_array($type, array_keys($G_ServerList))){
    die('[/*不支持的浏览器类型*/]');
}
//获取code

/**
 * 初始化
 */
function init(){


}