##F2ETEST 基于selenium2 & webdriverjs 的前端自动化测试平台

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
            	'screen': 'http:///a.com/sss/1.jpg'	// 截图图片地址
        	},
        	error: {
        	   msg: 'error msg'
        	}
    	}
