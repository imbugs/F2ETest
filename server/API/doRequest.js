var http = require("http");

http.createServer(function(request, response) {
    console.log('123'+request.url);

    //只接受这个请求
    if(request.url != '/doRequest'){
        response.writeHead(404, {"Content-Type": "text/plain"});
        response.end();
        return;
    }
    //GET请求由于数据量肯呢过会很大，不支持它
//    if(request.method == 'GET'){
//        response.writeHead(405, {"Content-Type": "text/plain"});
//        response.write("请使用POST方法！");
//        response.end();
//        return;
//    }

    //获取参数
    var data = querystring.parse(request.url);
    console.log(data);
    response.writeHead(200, {"Content-Type": "text/plain"});
    var webdriverjs = require("webdriverjs");

    var client = webdriverjs.remote({
        'host': '10.13.15.49',
        'port': 4444,
        "desiredCapabilities":{
            //"browserName":"internet explorer"
        }
    });

    var showTest = client.showTest;
    client.showTest = function(){
        showTest.apply( webdriverjs, arguments);
        response.write('123');
        response.write(arguments[1]);
    }

    require("../writable/cases/1").run(client, response);


}).listen(8888);
