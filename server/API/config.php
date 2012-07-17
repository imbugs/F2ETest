<?php
/**
 * 全局配置文件
 */
define('ROOT', dirname(__FILE__).'/');
$G_Debug = strstr($_SERVER['REQUEST_URI'], 'tdebug');
//服务器列表配置
$G_ServerList = array(
    'ie6' => array(
        array('ip'=>'10.13.51.85', 'port'=>4444),
        array('ip'=>'10.13.51.97', 'port'=>4444),
        array('ip'=>'10.13.15.47', 'port'=>4444)
    ),
    'ie7' => array(
        array('ip'=>'10.13.51.83', 'port'=>4444)
    ),
    'ie8' => array(
        array('ip'=>'10.13.51.82', 'port'=>4444)
    ),
    'ie9' => array(

    ),
    'ie10' => array(
    ),
    'firefox' => array(
        array('ip'=>'10.13.51.82', 'port'=>4444),
        array('ip'=>'10.13.51.83', 'port'=>4444)
    ),
    'chrome' => array(
        array('ip'=>'10.13.51.83', 'port'=>4444),
        array('ip'=>'10.13.51.85', 'port'=>4444)
    ),
    'opera' => array(
        array('ip'=>'10.13.51.82', 'port'=>4444),
        array('ip'=>'10.13.51.83', 'port'=>4444),
        array('ip'=>'10.13.51.85', 'port'=>4444)
    ),
    'htmlunit' => array(
        array('ip'=>'10.13.51.82', 'port'=>4444),
        array('ip'=>'10.13.51.83', 'port'=>4444),
        array('ip'=>'10.13.51.85', 'port'=>4444)
    )
);
//用例文件目录
$G_CasePath = ROOT.'../writable/cases/';
//文件截图目录
$G_ScreenPath = ROOT.'../writable/screenshots/';
//writeAble http地址
$G_WriteURL = 'http://'.$_SERVER['HTTP_HOST'].'/server/writable/';
//doRequest PHP http地址
$G_WriteURL = 'http://'.$_SERVER['HTTP_HOST'].'/server/writable/';
//NodeJs运行URL
$G_NodeURL = 'http://localhost:8800/';

$G_Case_header = "exports.run = function(client, response, next ){\n";
$G_Case_footer = "\n client.saveScreenshot();\n client.end(function( logs, results ){ next( logs, results ); } ); \n};";

//邮件列表
$G_MailListPath = ROOT.'../writable/mail_list.xml';
