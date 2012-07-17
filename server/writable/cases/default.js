exports.run = function(client, response, next ){
    client
        // 初始化
        .init()
        // 添加一个 Suite
        .describe( 'Main Suite', function (){

            // 添加一个子 Suite
            this.describe( 'Child Suite', function (){

                // 添加一个 Specification
                this.it( 'Simple Spec', function (){

                    this.expect( 1 ).not.toBe( 2 );
                    this.expect( 'hellohahaa').toMatch( '^hello.+' );
                    this.expect( 'hello' ).toBeDefined();

                    // 测试错误的情况
                    this.expect( 1).toBe( 2 );

                    this.expect( undefined).toBeUndefined();
                    this.expect( null).toBeNull();
                });
            });

            // 打开一个页面
            this.url("http://www.google.com", function(){

                this.it( 'title Test', function (){

                    // 获取页面标题
                    this.getTitle(function ( title ){

                        this.expect( title ).toBe( 'Google' );

                        // 填写表单
                        this.setValue("#lst-ib", "webdriver")

                            // 获取表单域值
                            .getValue( '#lst-ib', function ( value ){

                                // 比较 断言
                                this.expect( value ).toBe( 'webdriver' );
                            })

                            // 提交表单
                            .submitForm("#tsf");
                    }) ;
                });
            });
        })

        // 再建立一个 Suite
        .describe( 'The Other Main Suite', function (){

            this.it( 'The Other Simple Spec', function (){

                var currentTime;

                // 自定义，do方法只是让里面的自定义脚本和其他方法一起顺序执行
                this.do(function (){

                    currentTime = Date.now();
                })

                    // 暂停2秒
                    .pause( 2, function (){

                    this.expect( Date.now() - currentTime > 0).toBe( true );
                } )

                    // 截个图吧 - 截图可以在测试结果的log中查看到
                    .saveScreenshot();
            });
        });
 client.saveScreenshot();
 client.end(function( logs, results ){ next( logs, results ); } ); 
};