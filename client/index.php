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
    <div id="content" class="container">
        <div id="header" class="hero-unit">
            <h1>F2E 自动化页面测试</h1>
        </div>
        <div id="main" class="">
            <div id="input-wrap" class="span11">

                <div id="script-wrap" style=""><?php include( "exampleCode.php" ); ?></div>
                <p id="browsers" class="span6">
                    <span class="browser chrome" data-type="chrome"><input type="checkbox" value="chrome">chrome</span>
                    <span class="browser firefox" data-type="firefox"><input type="checkbox" value="firefox">firefox</span>
                    <span class="browser opera" data-type="opera"><input type="checkbox" value="opera">opera</span>
                    <span class="browser ie6" data-type="ie6"><input type="checkbox" value="ie6">ie6</span>
                    <span class="browser ie7" data-type="ie7"><input type="checkbox" value="ie7">ie7</span>
                    <span class="browser ie8" data-type="ie8"><input type="checkbox" value="ie8">ie8</span>
                    <span class="browser ie9" data-type="ie9"><input type="checkbox" value="ie9">ie9</span>
                </p>
                <p class="span1 offset3">
                    <input id="run-test-btn" type="button" class="btn btn-primary btn-large" value="测试" >
                </p>
                <div class="input-error alert alert-error span10">
                </div>

            </div>
            <div id="output-wrap" class="span11">
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
            <ul class="test-info-logs">
                <% for( var i = 0, log; log = logs[ i ]; i++ ){ %>
                    <li class="test-info-log">
                        <span class="log-type"><%=log.level%></span>
                        <span class="log-text"><%=log.msg%></span>
                        <% if( log.screenshot ) { %>
                        <span class="log-text">图片路径：<%=log.screenshot%></span>
                        <% } %>
                    </li>
                <% } %>
            </ul>
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