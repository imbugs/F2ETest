<?php
/**
 * ���Խ����jsonչ�ַ�ʽ
 * @param types ����������б�
 * @param testFile ���Խű���
 * @return jsonp
 * @note
 * ���ص����ݽ��Ϊ
 * jsonp({
 *      passrate: 1,    //ͨ���ʣ��ɹ��������/��Ч���������
 *      result: [
 *          'ie6': [
 *              passed: true    //�Ƿ�ͨ��
 *          ]��
 *          'ie7': [
 *              passed: false
 *          ]
 *      ]
 * })
 */
require('./common.php');

$json = array(
    'passrate' => 1,
    'result' => array()
);
$types = explode('|', $_REQUEST['types']);
$testFile = $_REQUEST['testFile'];
$jsPath = $G_CasePath.$testFile;
$host = $_REQUEST['host'];

if(count($types) == 0 || $testFile == ''){
    echo json_encode($json);
    die();
}

$all_count = 0;
$avi_count = 0;
while($type = array_pop($types)){
    $all_count++;

    $ret = doRequest($type, '', $jsPath, $host);
    if(!$ret['errorMsg'] != ''){
        $avi_count++;
        $json['result'][$type]['passed'] = true;
    }
}

?>