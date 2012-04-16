var http = require("http");
var querystring = require('querystring');

http.createServer(function(request, response) {
    //获取参数
    response.writeHead(200, {"Content-Type": "text/plain"});
    var query = request.url;
    if(!query){
        errorMsg('来路不正确', response);
        return;
    }
    var data = querystring.parse(query);
    console.log(data);

    if(!(data.path && data.type && data.ip && data.port)){
        errorMsg('参数不完整，需要四个参数', response);
        return;
    }

    //ie的不同版本需要单独处理
    if('^/ie\d$/'.test(data.type)){
        data.type = 'internet explorer';
    }
    var webdriverjs = require("webdriverjs");

    var client = webdriverjs.remote({
        'host': data.ip,
        'port': data.port,
        "desiredCapabilities":{
            "browserName":data.type
        },
        screenshotPath: '../writable/Scrennshots/'
    });

    var showTest = client.showTest;
    client.showTest = function(){
        showTest.apply( webdriverjs, arguments);
        response.write(arguments[1]);
    }

    require(data.path).run(client, response);
}).listen(8888);

/**
 * 错误信息
 * @param msg
 * @param res
 */
function errorMsg(msg, res){
    var ret = {
        'error':{
            'msg': msg
        }
    };
    res.write(JSON.stringify(ret));
    res.end();
}