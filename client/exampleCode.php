// 初始化
client.init()
    // 打开一个页面
    .url("http://www.google.com", function(){

        // 自定义log
        this.log( 'url finished!' );

        // 获取页面标题
        this.getTitle(function (){

            this.log( 'title get!' );

            // 设置表单域
            this.setValue("#lst-ib", "webdriver")

                // 提交表单
                .submitForm("#tsf", function(){

                    this.log( 'form submmit!' );
                });

        }) ;
    })

    // 暂停
    .pause( 1000, function (){

        this.log( 'pause!' );
    });

    // 不需要调用end()方法

