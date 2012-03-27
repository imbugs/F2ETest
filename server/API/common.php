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
    $header = "exports.run = function(client, response){\n";
    $footer = "\n};";

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
?>