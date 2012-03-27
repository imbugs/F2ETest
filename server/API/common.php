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

function resultMsg($type, $logs = array(), $screen = '', $halt = true){
    $ret = array(
        'result' => true,
        'data' => array(
            'type' => $type,
            'logs' => $logs,
            'srceen' => $screen
        )
    );
    echo json_encode($ret);
    if($halt){
        die();
    }}
/**
 * �������Ϣ
 * @param $msg ����ԭ��
 * @param $halt �Ƿ�ִֹͣ��
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
 * @param string $url   �򿪵�url������ http://www.cnlist.net/post.php
 * @param int $limit   ȡ���ص����ݵĳ���
 * @param string $post   Ҫ���͵� POST ���ݣ���c=cnlist&n=1234
 * @param string $cookie Ҫģ��� COOKIE ���ݣ���uid=123&auth=2323
 * @param bool $bysocket TRUE/FALSE �Ƿ�ͨ��SOCKET��
 * @param string $ip   IP��ַ
 * @param int $timeout   ���ӳ�ʱʱ��
 * @param bool $block   �Ƿ�Ϊ����ģʽ
 * @return    ȡ�����ַ���
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
?>