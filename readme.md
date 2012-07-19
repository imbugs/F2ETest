#F2ETEST 基于selenium2 & webdriverjs 的前端自动化测试平台
##如何让这个测试平台跑起来
###服务端
* 服务端需要有php运行环境
* 服务端需要有nodeJs运行环境
满足以上两条后运行

    node server/API/doResquest.js

推荐使用forever运行，以避免意外挂掉

    forever server/API/doResquest.js
###测试机配置
* 测试机没有多余要求，只要能够运行JAVA即可，最新的selenium-server-standalone-x.x.x.jar请到http://code.google.com/p/selenium/downloads/list下载
* windows机器请直接运行server/selenium.server/monitor.bat
* 其他平台请参照start.cmd 适当修改
* 测试机配置好厚需要修改服务端的server/API/config.php文件，增加相应的配置

##相关接口约定

### Server API

####serverStatus接口数据约定

* method: GET
* params: null
* return:
	
		[
        	'chrome',
        	'firefox',
        	'ie6',
        	'ie7',
        	'ie8',
        	'ie9',
        	'opera'
        ]
        

####doRequest运行结果数据

* method: POST
* params: 

		{
			type: 'chrome',					// 需要测试的浏览器类型
			testCode: 'your test code',		// 测试脚本
			host: '',                       // 用户自定义
			options: ''						// 相关配置… 
		}
* return:

    	{
        	'result': true,					// 请求结果
        	'data': {
            	'type': 'ie6',				// 测试的浏览器类型
            	'logs': [					// 测试结果的log数组
                	{
                    	'msg': 'abcdefghij',	// log输出
                    	'level': '1' //为1,2,3	// log输出的级别 分别对应 error warning info
               	 	}
            	],
            	'tests':{                   //测试结果输出
            	    'list': [{
            	        desciption: '结果集名称',
            	        result: false,      //结果集的最终结果
            	        specs: [],
            	        suites: [],
            	        summary: {}
            	    }],
            	    'summary: {},
            	    'type': 'chrome'
            	},
            	'script': '12345678900.js',       //此次测试的文件名
            	'screen': 'http:///a.com/sss/1.jpg'	// 截图图片地址
        	},
        	error: {
        	   msg: 'error msg'
        	}
    	}
