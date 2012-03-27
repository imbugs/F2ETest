<?php
include('./config.php');
/**
 * ���÷�������
 */

/**
 * ��鵥���������Ƿ����
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
 * ����б��з��������õĲ���
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
 * ����JS�ļ�
 * @param $code
 * @return string �����ļ�·��
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
 * ���˲���ȫ��js�ű�������ϵͳ�����ĵ��õ� TODO
 * @param $code
 * @return mixed
 */
function filterCode($code){
    return $code;
}
/**
 * ����127.0.0.1:4444 ����ip���˿�
 */
function getHost($host){
    if(preg_match("/^(\d+\.\d+\.\d+\.\d+)(\:(\d+)){0,1}$/", $host, $ret)){
        $ret[3] = $ret[3] ? $ret[3] : 4444;
        return array('ip' => $ret[1], 'port' => $ret[3]);
    }
    return false;
}
/**
 * ��ȡһ��ָ�����͵Ŀ��÷�����
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