var http = require("http");

http.createServer(function(request, response) {
    console.log('123'+request.url);

    //ֻ�����������
    if(request.url != '/doRequest'){
        response.writeHead(404, {"Content-Type": "text/plain"});
        response.end();
        return;
    }
    //GET�����������������ع���ܴ󣬲�֧����
//    if(request.method == 'GET'){
//        response.writeHead(405, {"Content-Type": "text/plain"});
//        response.write("��ʹ��POST������");
//        response.end();
//        return;
//    }

    //��ȡ����
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
