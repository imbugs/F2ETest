<?php
/**
 * @overview ���������б�����ԣ������ؽ��
 */
$serverList = array(
    array('id'=>1,'type'=>'ie6','ip'=>'10.13.15.49','port'=>4444),
    array('id'=>2,'type'=>'firefox','ip'=>'10.13.15.49','port'=>4444),
    array('id'=>3,'type'=>'chrome','ip'=>'10.13.15.49','port'=>4444),
    array('id'=>4,'type'=>'opera','ip'=>'10.13.15.49','port'=>4444)
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
    for($i = 0; $i < count($list); $i++){
        $item = $list[$i];
        if(isServerUp($item['ip'], $item['port'])){
            $result[] = array('id'=>$item['id'], 'type'=>$item['type']);
        }
    }
    return $result;
}

$output = serverStatus($serverList);
echo json_encode($output);
