define( [ '../config', '../common/command' ], function( cfg, command ){

    var models = cfg.models;
    /**
     * 单个浏览器测试结果视图
     */
    var testInfoItemModel = models[ 'testInfoItem' ] = Backbone.Model.extend({

        initialize: function (){

            var data = this.toJSON();

            this.fetch();

        },

        fetch: function (){

            var m = this.toJSON();
            var that = this;
            var data = {
                type: m.type,
                testCode: encodeURIComponent( m.testCode )
            };

           command.requestTest( data, function ( data ){

                that.dataHandle( data );
           });
        },

        /**
         * 对返回的数据进行预处理
         * @param data
         */
        dataHandle: function ( data ){

            var logs;
            var testResult;
            var screenShot;
            var type;
            var _data = data.data;
            var error = data.error;

            window[ 'main' ]['curData'] = _data;

            if( data.result ){

                logs = _data.logs || [];
                testResult = _data.tests;
                screenShot = _data.screen;

                this.set({
                    stat: 'finished',
                    logs: logs,
                    testResult: testResult,
                    screenshot: screenShot,
                    result: true
                });
            }
            else {

                // 若出错，则添加错误字段
                this.set({
                    stat: 'error',
                    result: false,
                    error: error.msg
                });
            }
        },

        defaults: {
            stat: 'testing', // testing | finished | error,
            defaultActive: false,
            testCode: '',
            screenshot: '',
            logs: '',
            testResult: [],
            type: 'browser',
            result: true,
            error: ''
        }
    });

    return testInfoItemModel;
});