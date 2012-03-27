var http = require("http");
var querystring = require('querystring');

http.createServer(function(request, response) {
    //��ȡ����
    response.writeHead(200, {"Content-Type": "text/plain"});
    var query = request.url;
    if(!query){
        errorMsg('��·����ȷ', response);
        return;
    }
    var data = querystring.parse(query);
    console.log(data);

    if(!(data.path && data.type && data.ip && data.port)){
        errorMsg('��������������Ҫ�ĸ�����', response);
        return;
    }

    //ie�Ĳ�ͬ�汾��Ҫ��������
    if('^/ie\d$/'.test(data.type)){
        data.type = 'internet explorer';
    }
    var webdriverjs = require("webdriverjs");

    var client = webdriverjs.remote({
        'host': data.ip,
        'port': data.port,
        "desiredCapabilities":{
            "browserName":data.type
        }
    });

    var showTest = client.showTest;
    client.showTest = function(){
        showTest.apply( webdriverjs, arguments);
        response.write(arguments[1]);
    }

    require(data.path).run(client, response);
}).listen(8888);

/**
 * ������Ϣ
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