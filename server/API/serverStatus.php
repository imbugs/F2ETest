<?php
/**
 * @overview ���������б�����ԣ������ؽ��
 */
$serverList = array(
    'ie6' => array(
        array('ip'=>'10.13.15.49', 'port'=>4444),
        array('ip'=>'10.13.15.48', 'port'=>4444),
        array('ip'=>'10.13.15.47', 'port'=>4444)
    ),
    'ie7' => array(

    ),
    'ie8' => array(

    ),
    'ie9' => array(

    ),
    'ie10' => array(

    ),
    'firefox' => array(
        array('ip'=>'10.13.15.49', 'port'=>4444)
    ),
    'chrome' => array(
        array('ip'=>'10.13.15.49', 'port'=>4444)
    ),
    'opera' => array(
        array('ip'=>'10.13.15.49', 'port'=>4444)
    )
);
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

$output = serverStatus($serverList);
echo json_encode($output);
