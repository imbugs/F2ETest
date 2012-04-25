var http = require("http");
var url = require( 'url' );
var querystring = require('querystring');
var webdriverNode = require("webdriverNode");

// todo 添加守护进程
http.createServer(function(request, response) {

    var query = url.parse(request.url).query;
    var client;
    var data;

    // 获取请求参数
    if(!query){
        errorMsg('来路不正确', response);
        return;
    }

    data = querystring.parse(query);

    // 参数验证
    if(!( typeof data.path !== undefined &&
        typeof data.type !== undefined &&
        typeof data.ip !== undefined &&
        typeof data.port !== undefined )){

        errorMsg('参数不完整，需要四个参数', response);
        return;
    }

    //ie的不同版本需要单独处理
    if(/^ie\d$/.test(data.type)){
        data.type = 'internet explorer';
    }

    // 新建client实例
    client = webdriverNode.remote({
        'host': data.ip,
        'port': data.port,
        "desiredCapabilities":{
            "browserName":data.type
        },
        screenshotPath: '../writable/screenshots/'
    });

    // 执行用户脚本...并返回log
    require(data.path).run(client, response, function ( logs , tests){
        //关闭浏览器前 截图
        response.writeHead(200, {"Content-Type": "text/plain"});
        response.write( JSON.stringify( {'logs': logs, 'tests': tests} ) );
        response.end();
    });

}).listen(8800);

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