<?php
include('./common.php');

$type = '';
$testCode = '';
$host = '';

//浏览器类型，如果不指定将使用HtmlUnit模式
$type = isset($_REQUEST['type']) ? $_REQUEST['type'] :  'HtmlUnit';
if(!in_array($type, array_keys($G_ServerList))){
    die('[/*不支持的浏览器类型*/]');
}
//获取code
$testCode = filterCode($_REQUEST['testCode']);

//服务器配置
$host = $_REQUEST['host'];
$server = getHost($host);
if(!$server){
    $server = getServerByType($type);
}

//这样子都没办法找到服务器那就没办法咯
if(!$server){
    die();
}

function res
/**
 * 初始化
 */
function init(){


}