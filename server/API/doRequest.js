var http = require("http");
var querystring = require('querystring');
var fs = require('fs');

/**
 * 获取post信息
 * @param req
 * @param res
 * @param callback
 */
function getPost(req, res, callback){
    var info ='';
    req.addListener('data', function(chunk){
        info += chunk;
    })
    .addListener('end', function(){
        info = querystring.parse(info);
        callback(info);
    });
}
/**
 * 创建用例文件
 * @param code
 */
function createCaseFile(code){
    var file = {
      header: "exports.run = function(client, response){",
      footer: "};"
    };

    var content = file.header + code + file.footer;
    //使用时间戳加三位随机数生成文件名
    var name = +new Date() + Math.floor(Math.random()*1000) + '.js';

    fs.openSync(name, "a", 0777, function(e, fd){
        if(e) throw e;
        fs.writeSync(fd,content,function(e){
            if(e) throw e;
            fs.closeSync(fd);
        });
    });
    return name;
}

http.createServer(function(request, response) {
    console.log('123'+request.url);

    //只接受这个请求
    if(!/^\/doRequest\?/.test(request.url)){
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
    var query = request.url.replace('/doRequest?', '');
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
