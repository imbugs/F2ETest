var http = require("http");

http.createServer(function(request, response) {
    console.log(request.url);

    response.writeHead(200, {"Content-Type": "text/plain"});
    var webdriverjs = require("webdriverjs");

    var client = webdriverjs.remote({
        'host': '10.13.15.49',
        "desiredCapabilities":{
            //"browserName":"internet explorer"
        }
    });

    var tmp = client.showTest;
    client.showTest = function(){
        tmp.apply( webdriverjs, arguments);
        response.write('123');
        response.write(arguments[1]);
    }

require("../writable/cases/1").run(client, response);


}).listen(8888);
