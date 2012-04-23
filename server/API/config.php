<?php
/**
 * 全局配置文件
 */

//服务器列表配置
$G_ServerList = array(
    'ie6' => array(
        array('ip'=>'10.13.51.85', 'port'=>4444),
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
        array('ip'=>'10.13.51.85', 'port'=>4444)
    ),
    'chrome' => array(
        array('ip'=>'10.13.51.85', 'port'=>4444)
    ),
    'opera' => array(
        array('ip'=>'10.13.51.85', 'port'=>4444)
    ),
    'htmlunit' => array(
        array('ip'=>'10.13.51.85', 'port'=>4444)
    )
);
//用例文件目录
$G_CasePath = '../writable/cases/';
//文件截图目录
$G_ScreenPath = '../writable/screenshots/';
//writeAble http地址
$G_WriteURL = 'http://'.$_SERVER['HTTP_HOST'].'/server/writable/';
//NodeJs运行URL
$G_NodeURL = 'http://localhost:8800/';