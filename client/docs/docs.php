<div id="f2e&#33258;&#21160;&#21270;&#27979;&#35797;&#24179;&#21488;-&#25991;&#26723;&#20013;&#24515;"
><h1
  >F2E自动化测试平台 文档中心</h1
  ><hr
   /><div id="&#20160;&#20040;&#26159;f2e&#33258;&#21160;&#21270;&#27979;&#35797;&#24179;&#21488;"
  ><h3
    >什么是F2E自动化测试平台</h3
    ><p
    >F2E自动化测试平台是基于<a href="http://seleniumhq.org/docs/01_introducing_selenium.html"
      >Selenium 2</a
      >构建的用于前端测试的平台。</p
    ><p
    ><strong
      >自动化？好吧，自动化在开发中！！</strong
      ></p
    ></div
  ><div id="&#29305;&#28857;"
  ><h3
    >特点</h3
    ><ul
    ><li
      >使用JavaScript(实际上是nodeJS）编写测试用例。由于使用了<a href="https://github.com/neekey/webdriverNode"
	>webdriverNode</a
	>作为selenium RESTFul的接口实现，因此可以使用我们熟悉的js来编写测试脚本。</li
      ><li
      >由于使用了webdriver，所以对我们可以使用系统级别的权限进行浏览器操作！跨域？页面跳转？截图？恩，都没问题。</li
      ><li
      >其他… 用着再说吧<strong
	>&gt;0&lt;</strong
	></li
      ></ul
    ></div
  ><div id="hello-world"
  ><h3
    >Hello World</h3
    ><p
    >直接来写一段测试用例吧：</p
    ><pre
    ><code
      >// 初始化
client.init()
    // 打开一个Google首页
    .url(&quot;http://www.google.com&quot;, function(){

        // 自定义log
        this.log( '页面成功打开' );

        // 截个图留念
        this.saveScreenshot();

        // 获取页面标题
        this.getTitle(function ( title ){

            this.log( '页面标题为: ' + title );

            // 设置表单域
            this.setValue(&quot;#lst-ib&quot;, &quot;Hello World!&quot;)

                // 提交表单
                .submitForm(&quot;#tsf&quot;, function(){

                    // 再截个图吧
                    this.saveScreenshot();
                    this.log( '表单提交成功!' );
                });
        }) ;
    });
</code
      ></pre
    ><p
    >上面这段代码很简单，打开Google首页，然后搜索&quot;Hello World!&quot;</p
    ></div
  ><div id="&#24322;&#27493;&#26041;&#27861;&#21516;&#27493;&#39034;&#24207;&#25191;&#34892;"
  ><h3
    >异步方法同步顺序执行</h3
    ><p
    >webdriverNode中提供的所有方法都是异步的。为什么是异步？因为每次操作的原理都是：</p
    ><p
    ><code
      >webdriverNode --&gt; selsenium server --&gt; webdriver( Browser ) --&gt; Selenium Server --&gt; webdriverNode</code
      > 但是这些方法本身虽然是异步，但是他们会按照被调用的顺序，同步执行。举个栗子：</p
    ><pre
    ><code
      >Client.a()
    .b(function(){

        this.c();
        this.d({
            this.e();
        });
    })
    .f();
</code
      ></pre
    ><p
    >上面这段代码的执行顺序是 <code
      >a -&gt; b -&gt; c -&gt; d -&gt; e -&gt; f</code
      >, 表明webdriverNode中的方法是支持嵌套的。具体的原理和细节，可以参考<a href="https://github.com/neekey/syncrun"
      >SyncRun</a
      >.</p
    ></div
  ><div id="&#30446;&#21069;&#25552;&#20379;&#30340;&#26041;&#27861;api"
  ><h3
    >目前提供的方法<a href="http://test.f2e.taobao.net/server/node_modules/webdriverNode/docs/index.html"
      >API</a
      ></h3
    ></div
  ></div
>
