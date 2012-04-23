<?php
include('./config.php');
/**
 * 公用方法定义
 */

/**
 * 检查单个服务器是否可用
 * @param $ip
 * @param int $port
 * @return bool
 */
function isServerUp($ip, $port=4444){
    $fp = @fsockopen($ip , $port, $errNo , $errstr, 0.8 );
    if( !$fp ){
        return false;
    }else{
        fclose($fp);
        return true;
    }
}

/**
 * 检查列表中服务器可用的部分
 * @param $list
 * @return array
 */
function serverStatus($list){
    $result = array();

    foreach($list as $type => $servers){
        for($i = 0; $i < count($servers); $i++){
            $item = $servers[$i];
            if(isServerUp($item['ip'], $item['port'])){
                $result[] = $type;
                break;
            }
        }
    }
    return $result;
}

/**
 * 创建JS文件
 * @param $code
 * @return string 返回文件路径
 */
function createTestJS($code){
    global $G_CasePath;
    $header = "exports.run = function(client, response, next ){\n";
    $footer = "\n client.saveScreenshot();\n client.end(function( logs ){ next( logs ); } ); \n};";

    $filepath =  $G_CasePath.time().rand(100, 999).'.js';


    $fp = fopen($filepath,"a");
    fwrite($fp, $header.$code.$footer);
    fclose($fp);

    return $filepath;
}
/**
 * 过滤不安全的js脚本，比如系统函数的调用等 TODO
 * @param $code
 * @return mixed
 */
function filterCode($code){
    $black = array('spawn');

    $code = str_replace($black, '', $code);
    return $code;
}
/**
 * 根据127.0.0.1:4444 返回ip、端口
 */
function getHost($host){
    if(preg_match("/^(\d+\.\d+\.\d+\.\d+)(\:(\d+)){0,1}$/", $host, $ret)){
        $ret[3] = $ret[3] ? $ret[3] : 4444;
        return array('ip' => $ret[1], 'port' => $ret[3]);
    }
    return false;
}
/**
 * 获取一个指定类型的可用服务器
 * @param $type
 * @return array
 */
function getServerByType($type){
    global $G_ServerList;
    $list = $G_ServerList[$type];
    foreach($list as $i){
        if(isServerUp($i['ip'], $i['port'])){
            return $i;
        }
    }
    return false;
}

function resultMsg($type, $logs = array(), $screen = '', $halt = true){
    $ret = array(
        'result' => true,
        'data' => array(
            'type' => $type,
            'logs' => $logs,
            'screen' => $screen
        )
    );
    echo json_encode($ret);
    if($halt){
        die();
    }}
/**
 * 输出错信息
 * @param $msg 错误原因
 * @param $halt 是否停止执行
 */
function errorMsg($msg ,$halt = true){
    $ret = array(
        'result'=> false,
        'error'=>array(
            'msg'=> $msg
        )
    );
    echo json_encode($ret);
    if($halt){
        die();
    }
}
/**
 * @param string $url   打开的url，　如 http://www.cnlist.net/post.php
 * @param int $limit   取返回的数据的长度
 * @param string $post   要发送的 POST 数据，如c=cnlist&n=1234
 * @param string $cookie 要模拟的 COOKIE 数据，如uid=123&auth=2323
 * @param bool $bysocket TRUE/FALSE 是否通过SOCKET打开
 * @param string $ip   IP地址
 * @param int $timeout   连接超时时间
 * @param bool $block   是否为阻塞模式
 * @return    取到的字符串
 */
function uc_fopen($url, $limit = 0, $post = '', $cookie = '', $bysocket = FALSE, $ip = '', $timeout = 15, $block = TRUE) {
    $return = '';
    $matches = parse_url($url);
    !isset($matches['host']) && $matches['host'] = '';
    !isset($matches['path']) && $matches['path'] = '';
    !isset($matches['query']) && $matches['query'] = '';
    !isset($matches['port']) && $matches['port'] = '';
    $host = $matches['host'];
    $path = $matches['path'] ? $matches['path'].($matches['query'] ? '?'.$matches['query'] : '') : '/';
    $port = !empty($matches['port']) ? $matches['port'] : 80;
    if($post) {
        $out = "POST $path HTTP/1.0\r\n";
        $out .= "Accept: */*\r\n";
        //$out .= "Referer: $boardurl\r\n";
        $out .= "Accept-Language: zh-cn\r\n";
        $out .= "Content-Type: application/x-www-form-urlencoded\r\n";
        $out .= "User-Agent: $_SERVER[HTTP_USER_AGENT]\r\n";
        $out .= "Host: $host\r\n";
        $out .= 'Content-Length: '.strlen($post)."\r\n";
        $out .= "Connection: Close\r\n";
        $out .= "Cache-Control: no-cache\r\n";
        $out .= "Cookie: $cookie\r\n\r\n";
        $out .= $post;
    } else {
        $out = "GET $path HTTP/1.0\r\n";
        $out .= "Accept: */*\r\n";
        //$out .= "Referer: $boardurl\r\n";
        $out .= "Accept-Language: zh-cn\r\n";
        $out .= "User-Agent: $_SERVER[HTTP_USER_AGENT]\r\n";
        $out .= "Host: $host\r\n";
        $out .= "Connection: Close\r\n";
        $out .= "Cookie: $cookie\r\n\r\n";
    }
    $fp = @fsockopen(($ip ? $ip : $host), $port, $errno, $errstr, $timeout);
    if(!$fp) {
        return '';//note $errstr : $errno \r\n
    } else {
        stream_set_blocking($fp, $block);
        stream_set_timeout($fp, $timeout);
        @fwrite($fp, $out);
        $status = stream_get_meta_data($fp);
        if(!$status['timed_out']) {
            while (!feof($fp)) {
                if(($header = @fgets($fp)) && ($header == "\r\n" || $header == "\n")) {
                    break;
                }
            }
            $stop = false;
            while(!feof($fp) && !$stop) {
                $data = fread($fp, ($limit == 0 || $limit > 8192 ? 8192 : $limit));
                $return .= $data;
                if($limit) {
                    $limit -= strlen($data);
                    $stop = $limit <= 0;
                }
            }
        }
        @fclose($fp);
        return $return;
    }
}
/**
 * 根据URL获取文件名
 * @param $url
 * @return mixed
 */
function retrieve($url)
{
    preg_match('/\/([^\/]+\.[a-z]+)[^\/]*$/',$url,$match);
    return $match[1];
}
/**
 * 格式化日志，对日志做必要的处理
 * 1、处理screenshotSave类型日志，将其转换为可访问url
 * @param array $logs
 * @return array
 */
function formatLogs($logs = array()){
    global $G_WriteURL;
    for($i = 0; $i < count($logs); $i++){
        switch($logs[$i]->type){
            case 'screenshotSave':
                $logs[$i]->screenshot = $G_WriteURL .'screenshots/'.retrieve($logs[$i]->screenshot);
                break;
        }
    }
    return $logs;
}
function getLastScreenShot($logs = array()){
    $screenShot = '';
    for($i = count($logs)-1; $i >= 0; $i--){
        switch($logs[$i]->type){
            case 'screenshotSave':
                $screenShot = $logs[$i]->screenshot;
                break;
        }
        if($screenShot) break;
    }
    return $screenShot;
}
?>