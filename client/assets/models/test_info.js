define( [ '../config', '../common/command' ], function( cfg, command ){

    var models = cfg.models;

    var testInfoModel = Backbone.Model.extend({

        initialize: function (){

            this.getBrowserStat();
        },

        defaults: {
            ifCheckStat: false,
            testCode: '',
            defaultActive: '',
            availableBrowser: [],
            isTesting: false,
            requestBrowser: [],
            finishedBrowser: []
        },

        getBrowserStat: function (){

            var that = this;

            command.getBrowserStat(function ( data ){

                data = that.statDataHanele( data );

                that.set({
                    ifCheckStat: true,
                    availableBrowser: data
                });
            });
        },

        statDataHanele: function ( data ){

            var dataHandled = [];

            _.each( data, function ( item ){

                dataHandled.push( item );
            });

            return dataHandled;
        },

        /**
         * 对数据进行验证
         */
        validation: function (){

            var data = this.toJSON();
            var requestBrowser = data.requestBrowser;
            var testCode = $.trim( data.testCode );
            var result = true;
            var msg = '';

            if( requestBrowser.length === 0 ){

                msg += '必须制定需要测试的浏览器;\n';
                result = false;
            }

            if( !testCode ){

                msg += '测试代码不能为空';
                result = false;
            }

            if( !result ){

                msg = '错误！' + msg;
            }

            return {
                result: result,
                msg: msg
            };

        }
    });

    return testInfoModel;
});