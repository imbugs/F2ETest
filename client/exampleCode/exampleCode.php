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