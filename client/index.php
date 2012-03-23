<!DOCTYPE HTML>
<html >
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="assets/lib/bootstrap/css/bootstrap.min.css" type="text/css">
    <link rel="stylesheet" href="assets/index.css" type="text/css">
    <script type="text/javascript" src="assets/lib/underscore-min.js"></script>
    <script type="text/javascript" src="assets/lib/jquery-1.7.1.min.js"></script>
    <script type="text/javascript" src="assets/lib/backbone-min.js"></script>
    <script type="text/javascript" src="assets/index.js"></script>
    <title></title>
</head>
<body>
    <div id="content" class="container">
        <div id="header" class="hero-unit">
            <h1>F2E 自动化页面测试</h1>
        </div>
        <div id="main" class="span12">
            <div id="input-wrap">

                <p id="script-wrap">
                    <textarea id="testcode-area" class="span11" placeholder="输入你的测试代码"></textarea>
                </p>
                <p id="browsers" class="span6">
                    <span class="browser chrome" data-type="chrome"><input type="checkbox" value="chrome">chrome</span>
                    <span class="browser firefox" data-type="firefox"><input type="checkbox" value="firefox">firefox</span>
                    <span class="browser opera" data-type="opera"><input type="checkbox" value="opera">opera</span>
                    <span class="browser ie6" data-type="ie6"><input type="checkbox" value="ie6">ie6</span>
                </p>
                <p class="span2 offset3">
                    <input type="button" class="btn btn-primary" value="测试" >
                </p>

            </div>
            <div id="output-wrap">
                <div id="output-tabs">
                    <div class="hd">


                    </div>
                    <div class="bd">


                    </div>
                </div>
            </div>
        </div>
        <div id="footer"></div>
    </div>

</body>
</html>