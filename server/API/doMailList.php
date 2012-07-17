<?php
include('./common.php');

/**
 * 增删定时任务列表
 */

if(is_dir($G_MailListPath) || !file_exists($G_MailListPath)){
    $fp = fopen($G_MailListPath, "w+");
    fputs($fp, '<?xml version="1.0"?><root></root>');
    fclose($fp);
}
$mail_list = new DomDocument();
$mail_list->load($G_MailListPath);
$xpath = new domxpath($mail_list);

function addJobs($script, $emails, $title){
    global $mail_list,  $G_MailListPath;

    if(!$emails || !$script){
        return;
    }

    $newJob = new DOMElement("job");
    $mail_list->firstChild->appendChild($newJob);
    $newJob->setAttribute('script', $script);
    $newJob->setAttribute('title', $title);
    $newJob->appendChild(new DOMElement('email', $emails));

    $mail_list->formatOutput = true;
    $mail_list->saveXML();
    $mail_list->save($G_MailListPath);
}
function deleteJob($script){
    global $mail_list,$xpath, $G_MailListPath;

    if($script){
        $jobs = $xpath->query("//job[@script='$script']");
        foreach($jobs as $job){
                $mail_list->firstChild->removeChild($job);
        }
    }

    $mail_list->formatOutput = true;
    $mail_list->saveXML();
    $mail_list->save($G_MailListPath);
}
/**
 * 根据json数据格式化邮件
 * @param $message 获取到的json数据
 * @return string 格式化后的html模板
 */
function _makeEMail($url){
    $message = uc_fopen($url);
    $NO_RESULT_TMP = '<p class="MsoNormal"><span style="font-family:&quot;\005fae\008f6f\0096c5\009ed1&quot;,&quot;sans-serif&quot;">对不起测试系统未返回数据，可能是测试集群挂了！</span></p><p class="MsoNormal"></p><p class="MsoNormal"align="right"style="text-align:right"><span style="font-size:9.0pt;color:#1f497d">F2E自动化页面测试，有任何问题请联系溪夏，隐若。</span></p>';
    $OK_RESULT_TMP = '<p class="MsoNormal"><b><span style="font-size:18.0pt;font-family:&quot;\005fae\008f6f\0096c5\009ed1&quot;,&quot;sans-serif&quot;;color:#00b050">恭喜</span></b><span style="font-family:&quot;\005fae\008f6f\0096c5\009ed1&quot;,&quot;sans-serif&quot;">，您的测试用于完全通过！</span></p><p class="MsoNormal"></p><h3 style="margin:0cm;margin-bottom:.0001pt;line-height:20.25pt"><span style="font-size:12.0pt;font-family:&quot;\005fae\008f6f\0096c5\009ed1&quot;,&quot;sans-serif&quot;;color:#00b050">测试统计：<span>Suite:{{suite}},Spec:{{spec}},Assert:{{item}}</span></span></h3><p class="MsoNormal"></p><p class="MsoNormal"><span style="font-family:&quot;\005fae\008f6f\0096c5\009ed1&quot;,&quot;sans-serif&quot;">详情请访问：<span><a href="{{detailURI}}" target="_blank">{{detailURI}}</a></span></span></p><p class="MsoNormal"><span style="font-family:&quot;\005fae\008f6f\0096c5\009ed1&quot;,&quot;sans-serif&quot;">&nbsp;</span></p>';
    $ERR_RESULT_TMP = '<p class="MsoNormal"><b><span style="font-size:18.0pt;font-family:&quot;\005fae\008f6f\0096c5\009ed1&quot;,&quot;sans-serif&quot;;color:#c0504d">出问题了</span></b><span style="font-family:&quot;\005fae\008f6f\0096c5\009ed1&quot;,&quot;sans-serif&quot;">，您的测试用例未完全通过！</span></p><p class="MsoNormal"></p><p class="MsoNormal"align="left"style="text-align:left;line-height:20.25pt"><b><span style="font-size:12.0pt;font-family:&quot;\005fae\008f6f\0096c5\009ed1&quot;,&quot;sans-serif&quot;;color:#00b050">测试统计：<span>Suite:{{suite}},Spec:{{spec}},Assert:{{item}}</span></span></b><b><span style="font-size:12.0pt;font-family:&quot;\005fae\008f6f\0096c5\009ed1&quot;,&quot;sans-serif&quot;;color:#c0504d">,&nbsp;Suite-Failure:{{suiteFailure}},Spec-Failure:{{specFailure}},Assert-Failure:{{itemFailure}}</span></b><b><span style="font-size:12.0pt;font-family:&quot;\005fae\008f6f\0096c5\009ed1&quot;,&quot;sans-serif&quot;;color:#00b050"></span></b></p><p class="MsoNormal"><span style="font-family:&quot;\005fae\008f6f\0096c5\009ed1&quot;,&quot;sans-serif&quot;">&nbsp;</span></p><p class="MsoNormal"><span style="font-family:&quot;\005fae\008f6f\0096c5\009ed1&quot;,&quot;sans-serif&quot;">详情请访问：<span><a href="{{detailURI}}"target="_blank">{{detailURI}}</a></span></span></p>';

    $MSG = json_decode($message);
    if(count($MSG->result) == 0){
        return $NO_RESULT_TMP;
    }
    //此处简单处理第一个浏览器结果，等待邮件模板加强
    foreach($MSG->result as $result){
        break;
    };
    if($result->tests->summary->suiteFailure  == 0){
        $formatted = $OK_RESULT_TMP;
    }else{
        $formatted = $ERR_RESULT_TMP;
    }

    $formatted = str_replace("{{item}}", $result->tests->summary->item, $formatted);
    $formatted = str_replace("{{itemFailure}}", $result->tests->summary->itemFailure, $formatted);
    $formatted = str_replace("{{spec}}", $result->tests->summary->spec, $formatted);
    $formatted = str_replace("{{specFailure}}", $result->tests->summary->specFailure, $formatted);
    $formatted = str_replace("{{suite}}", $result->tests->summary->suite, $formatted);
    $formatted = str_replace("{{suiteFailure}}", $result->tests->summary->suiteFailure, $formatted);
    $formatted = str_replace("{{detailURI}}",
        str_replace('server/API/multiTestJson.php', 'client/index.php', $url)
        , $formatted);
    echo $formatted;
    return $formatted;
}
function mailInfo($mail_list_array){
    //加一个若有若无的安全限制
    if($_GET['token'] != '9527'){
        return;
    }

    $jobs = $mail_list_array->job;
    foreach($jobs as $job){
        $message = _makeEMail($job->attributes()->script);
        $to = $job->email;
        $subject = $job->attributes()->title;

        $from = 'F2ETest <F2ETest@feeqi.com>';//发件人地址
        $headers = 'MIME-Version: 1.0' . "\r\n";
        $headers .= 'Content-type: text/html; charset=gb2312' . "\r\n";
        $headers .= 'From: ' . $from . "\r\n";
        mail($to, $subject, $message, $headers);
    }
}


$mail_list_array = simplexml_import_dom($mail_list);
switch($_GET['action']){
    case 'add':
        addJobs($_GET['script'], $_GET['emails'], $_GET['title']);
        break;
    case 'del':
        deleteJob($_GET['script']);
        break;
    case 'mail':
        mailInfo($mail_list_array);
        break;
}

echo jsonp_encode($mail_list_array);
?>