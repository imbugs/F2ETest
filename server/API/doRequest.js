var http = require("http");
var querystring = require('querystring');
var fs = require('fs');

/**
 * ��ȡpost��Ϣ
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
 * ���������ļ�
 * @param code
 */
function createCaseFile(code){
    var file = {
      header: "exports.run = function(client, response){",
      footer: "};"
    };

    var content = file.header + code + file.footer;
    //ʹ��ʱ�������λ����������ļ���
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

    //ֻ�����������
    if(!/^\/doRequest\?/.test(request.url)){
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
