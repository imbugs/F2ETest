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
if(!in_array($type, array_keys($G_ServerList))){
    errorMsg('不支持的浏览器类型');
}

//服务器配置
$host = $_REQUEST['host'];
$server = getHost($host);
if(!$server){
    $server = getServerByType($type);
}

//这样子都没办法找到服务器那就没办法咯
if(!$server){
    errorMsg('没有可用服务器');
}

//获取code
$testCode = $_REQUEST['testCode'];

// 对代码进行转码 但是其中的 ' 会被转化为 \'
$testCode = urldecode( $testCode );
// 将 \' 转化回 '
$testCode = stripslashes( $testCode );
// 对代码进行过滤处理
$testCode = filterCode( $testCode );

// 创建用于执行的js模块文件
$jsPath = createTestJS($testCode);

$url = $G_NodeURL."?path=$jsPath&type=$type&ip=".$server['ip']."&port=".$server['port'];

$remoteMsg = uc_fopen($url);
$remoteMsg = json_decode($remoteMsg);

resultMsg($type, $remoteMsg, $remoteMsg['screen']);
