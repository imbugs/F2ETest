#F2E自动化测试平台  文档中心

-----

###什么是F2E自动化测试平台


F2E自动化测试平台是基于[Selenium 2](http://seleniumhq.org/docs/01_introducing_selenium.html)构建的用于前端测试的平台。

**自动化？好吧，自动化在开发中！！**

###特点

* 使用JavaScript(实际上是nodeJS）编写测试用例。由于使用了[webdriverNode](https://github.com/neekey/webdriverNode)作为selenium RESTFul的接口实现，因此可以使用我们熟悉的js来编写测试脚本。
* 由于使用了webdriver，所以对我们可以使用系统级别的权限进行浏览器操作！跨域？页面跳转？截图？恩，都没问题。
* 其他… 用着再说吧**>0<**

###Hello World

直接来写一段测试用例吧：

    // 初始化
    client.init()
        // 打开一个Google首页
        .url("http://www.google.com", function(){
    
            // 自定义log
            this.log( '页面成功打开' );
            
            // 截个图留念
            this.saveScreenshot();
    
            // 获取页面标题
            this.getTitle(function ( title ){
    
                this.log( '页面标题为: ' + title );
    
                // 设置表单域
                this.setValue("#lst-ib", "Hello World!")
    
                    // 提交表单
                    .submitForm("#tsf", function(){
    
    					// 再截个图吧
    					this.saveScreenshot();
                        this.log( '表单提交成功!' );
                    });
            }) ;
        });
        
上面这段代码很简单，打开Google首页，然后搜索"Hello World!"

###异步方法同步顺序执行

webdriverNode中提供的所有方法都是异步的。为什么是异步？因为每次操作的原理都是：

`webdriverNode --> selsenium server --> webdriver( Browser ) --> Selenium Server --> webdriverNode
`
但是这些方法本身虽然是异步，但是他们会按照被调用的顺序，同步执行。举个栗子：

	Client.a()
        .b(function(){
            
        	this.c();
        	this.d({
        		this.e();
        	});
        })
        .f();
        
上面这段代码的执行顺序是 `a -> b -> c -> d -> e -> f `, 表明webdriverNode中的方法是支持嵌套的。具体的原理和细节，可以参考[SyncRun](https://github.com/neekey/syncrun).

###目前提供的方法[API](http://test.f2e.taobao.net/server/node_modules/webdriverNode/docs/index.html)
###重要注意
弱国你要使用转义符“\”,请使用“\\”，系统会过滤一次“\”

    


