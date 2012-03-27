var http = require("http");
var querystring = require('querystring');

http.createServer(function(request, response) {
    //获取参数
    var query = request.url;
    if(!query){
        response.end();
        return;
    }
    var data = querystring.parse(query);
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
