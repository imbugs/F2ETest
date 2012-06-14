<?php
include('./common.php');

$type = '';
$testCode = '';
$jsPath = '';
$host = '';
$server = '';
$remoteMsg = '';

//浏览器类型，如果不指定将使用HtmlUnit模式
$type = isset($_REQUEST['type']) ? $_REQUEST['type'] :  'htmlunit';

//服务器配置
$host = $_REQUEST['host'];

$jsPath = $G_CasePath.$_REQUEST['testFile'];

$testCode = $_REQUEST['testCode'];

$ret = doRequest($type, $testCode, $jsPath, $host);

if($ret['errorMsg'] != ''){
    errorMsg($ret['errorMsg']);
}else{
    $args = $ret['result'];
    resultMsg($args[0], $args[1], $args[2], $args[3], $args[4]);
}
