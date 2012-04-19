<!DOCTYPE HTML>
<html >
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="assets/lib/bootstrap/css/bootstrap.min.css" type="text/css">
    <link rel="stylesheet" href="assets/index.css" type="text/css">
    <script type="text/javascript" src="assets/lib/underscore-min.js"></script>
    <script type="text/javascript" src="assets/lib/jquery-1.7.1.min.js"></script>
    <script type="text/javascript" src="assets/lib/backbone-min.js"></script>
    <script type="text/javascript" src="assets/lib/bootstrap/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="assets/index.js"></script>
    <script type="text/javascript" src="assets/lib/ace/build/src/ace.js"></script>
    <title></title>
</head>
<body>
<?php $pageName = 'index'; include "common/nav.php"; ?>
<div id="content" class="container">
        <div id="main" class="">
            <div class="alert alert-info" id="index-intro"><?php include "docs/indexIntro.php"; ?></div>
            <div id="input-wrap" class="span11">

                <div id="script-wrap" style=""><?php include("exampleCode/exampleCode.php"); ?></div>
                <p id="browsers" class="span6">
                    <span class="browser chrome" data-type="chrome"><input type="checkbox" value="chrome">chrome</span>
                    <span class="browser firefox" data-type="firefox"><input type="checkbox" value="firefox">firefox</span>
                    <span class="browser opera" data-type="opera"><input type="checkbox" value="opera">opera</span>
                    <span class="browser ie6" data-type="ie6"><input type="checkbox" value="ie6">ie6</span>
                    <span class="browser ie7" data-type="ie7"><input type="checkbox" value="ie7">ie7</span>
                    <span class="browser ie8" data-type="ie8"><input type="checkbox" value="ie8">ie8</span>
                    <span class="browser ie9" data-type="ie9"><input type="checkbox" value="ie9">ie9</span>
                </p>
                <p id="run-btn-wrap" class=" span1 offset3">
                    <input id="run-test-btn" type="button" class="btn btn-primary btn-large" value="测试" >
                </p>
                <div class="input-error alert span10">
                </div>

            </div>
            <div id="output-wrap" class="span11">
                <div class="log-filter-list">
                    <span class="label log-filter" data-type="command" data-label="label-info">command</span>
                    <span class="label log-filter" data-type="data" data-label="label-warning">data</span>
                    <span class="label log-filter" data-type="result" data-label="label-success">result</span>
                    <span class="label log-filter" data-type="error" data-label="label-important">error</span>
                    <span class="label log-filter" data-type="screenshotSave" data-label="label-inverse">screenshotSave</span>
                    <span class="label log-filter" data-type="custom" data-label="label-custom">custom</span>
                </div>
                <div id="output-tabs" class="tabbable">
                    <ul class="hd nav nav-tabs">
<!--                        <li class=""><a href="#tab1" data-toggle="tab">session 1</a></li>-->

                    </ul>
                    <div class="bd tab-content">
<!--                        <div class="tab-pane" id="tab1">tab1 content</div>-->
                    </div>
                </div>
            </div>
        </div>
        <div id="footer"></div>
    </div>

    <script type="text/html" id="test-info-pane-tpl">

        <div class="tab-pane test-info-pane <% if( defaultActive ){ %>active<% } %>" data-type="<%=type%>" id="tab-pane-<%=type%>">
<!--            若测试结果正常-->
            <% if( result === true ) { %>
            <ul class="test-info-logs ">
                <% for( var i = 0, log; log = logs[ i ]; i++ ){ %>
                    <li class="test-info-log log-item log-type-<%=log.type%>" >
                        <span class="log-type label <% switch( log.type ){
                            case 'command':
                                print( 'label-info' );
                                break;
                            case 'data':
                                print( 'label-warning');
                                break;
                            case 'result':
                                print( 'label-success' );
                                break;
                            case 'error':
                                print( 'label-important' );
                                break;
                            case 'screenshotSave':
                                print( 'label-inverse');
                                break;
                            case 'custom':
                                print( 'label-custom' );
                                break;
                        }
                        %>"><%=log.type%></span>
                           <span class="log-text"><% print(log.msg); if( log.screenshot ){ %>--- <a target="_blank" href="<%=log.screenshot%>">屏幕截图</a> <% } %></span>

                    </li>
                <% } %>
            </ul>
            <% } else { %>
<!--            若错误，则显示错误信息-->
            <div><div class="alert alert-error">测试出错啦！错误信息：<%=error%></div></div>
            <% } %>
            <div class="test-screenshot"><img src="<%=screenshot%>"></div>
        </div>
    </script>

    <script type="text/html" id="test-info-trigger-tpl">
        <li data-type="<%=type%>" class="test-info-trigger <% if( defaultActive ){ %>active<% } %>"><a href="#tab-pane-<%=type%>" data-toggle="tab"><%=type%></a></li>
    </script>
    <script type="text/javascript">

    </script>
</body>
</html>