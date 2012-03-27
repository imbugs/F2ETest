<?php
include('./config.php');


$type = '';
$testCode = '';

//浏览器类型，如果不指定将使用HtmlUnit模式
$type = isset($_REQUEST['type']) ? $_REQUEST['type'] :  'HtmlUnit';
if(!in_array($type, array_keys($G_ServerList))){
    die('[/*不支持的浏览器类型*/]');
}
//获取code
$testCode = filterCode($_REQUEST['testCode']);

/**
 * 过滤不安全的js脚本，比如系统函数的调用等 TODO
 * @param code $
 * @return mixed
 */
function filterCode(code){
    return code;
}
/**
 * 初始化
 */
function init(){


}