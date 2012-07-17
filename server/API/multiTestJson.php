<?php
/**
 * 测试结果的json展现方式
 * @param types 浏览器类型列表
 * @param testFile 测试脚本名
 * @return jsonp
 * @note
 * 返回的数据结果为
 * jsonp({
 *      passrate: 1,    //通过率，成功浏览器数/有效总浏览器数
 *      result: [
 *          'ie6': [
 *              tests: 。。。    //测试结果集
 *          ]，
 *          'ie7': [
 *              tests: 。。。
 *          ]
 *      ]
 * })
 */
require('./common.php');
header('Content-type: application/javascript');

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
        $json['result'][$type]['tests'] = $ret['result'][3];
    }
}
$json['passrate'] = $all_count == 0 ? 0 : $avi_count / $all_count;
echo jsonp_encode($json);
?>