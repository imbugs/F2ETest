(function(){

    var API = {

        BROWSER_STAT: 'http://localhost:8888/f2e-webDriver/fake/stat.php',
        REQUEST_TEST: 'http://localhost:8888/F2ETest/client/fake/test.php'

//        BROWSER_STAT: 'http://localhost:8888/F2ETest/server/API/serverStatus.php',
//        REQUEST_TEST: 'http://localhost:8888/F2ETest/server/API/doRequest.php'
    };

    $( document).ready(function (){

        var Main = new Views.Main({
            el: $( '#content' )
        });
        window[ 'main' ] = Main;

    });

    /*== util ==*/
    var command = {

        getBrowserStat: function ( next ){

            $.get( API.BROWSER_STAT, function ( data ){

                next( data );
            }, 'json' );

        },
        requestTest: function ( data, next ){

            $.post( API.REQUEST_TEST, data, next, 'json' );
        }
    };

    /*== views ==*/
    var Views = {

        Main: Backbone.View.extend({

            initialize: function (){

                this.browserListEl = $( '#browsers' );
//                this.codeTextarea = $( '#testcode-area' );

                this.codeEditorInit();
                this.model = new Models.TestInfo();
                this.TestInfo = new Views.TestInfo();
                this.alertEl = this.$( '.input-error').hide();

                this.attachModel();
            },

            /**
             * 初始化代码编辑器
             * todo 优化
             */
            codeEditorInit: function (){

                this.codeEditor = ace.edit("script-wrap")
                this.codeEditor.setTheme("ace/theme/twilight");
                this.codeEditor.getSession().setMode("ace/mode/javascript");
//                this.codeEditor.insert( '/* 在此处输入你的测试代码 */');
//                this.codeEditor.gotoLine( 3 );
            },

            events: {
                'change #testcode-area': 'onTestcodeChange',
                'click #browsers input[type=checkbox]': 'onRequestBrowsersChange',
                'click #run-test-btn': 'onRunBtnClick'
            },

//            render: function (){
//
//            },

            /**
             * 绑定model事件
             */
            attachModel: function (){

                var that = this;

                this.model.on( 'change:availableBrowser', function ( m, name ){

                    that.updateBrowserList();
                });
            },

            /**
             * 根据当前model.available的值，来更新浏览器选择列表
             */
            updateBrowserList: function (){

                var availableBrowser = this.model.get( 'availableBrowser' );
                var that = this;

                this.browserListEl.find( '.browser').each(function (){

                    if( _.indexOf( availableBrowser, $( this ).attr( 'data-type' ) ) >= 0 ){

                        that.toggleBrowserStat( this, true );
                    }
                    else {

                        that.toggleBrowserStat( this, false );
                    }
                });

//                console.log( 'test');
            },

            /**
             * 修改浏览器选择部分是否为可用状态
             * @param type
             * @param ifAble
             */
            toggleBrowserStat: function ( type, ifAble ){


                var browserSpan = $( typeof type === 'string' ? this.browserListEl.find( '.browser[data-type=' + type + ']') : type );

                if( ifAble ){

                    browserSpan.removeClass( 'unavailable' );
                    browserSpan.find( 'input').removeAttr( 'disabled' );
                }
                else {

                    browserSpan.addClass( 'unavailable' );
                    browserSpan.find( 'input').attr( 'disabled', 'disabled' );
                }

            },


            runTest: function (){

                var data = this.model.toJSON();
                var requestBrowser = data.requestBrowser;
                var testCode = data.testCode;
                var that = this;
                var firstTab;

                // 清空原有结果
                this.TestInfo.clear();

                _.each( requestBrowser, function ( browser ){

                    var data = {
                        testCode: testCode,
                        type: browser
                    };

                    if( !firstTab ){

                        firstTab = browser;
                        data.defaultActive = true;
                    }

                    that.TestInfo.addItem( data );
                });

            },

            showAlert: function( msg ){

                this.alertEl.html( msg );
                this.alertEl.fadeIn( 500 );
            },

            hideAlert: function (){

                this.alertEl.fadeOut( 500 );
            },

            /**
             * 当测试代码发生变化
             */
            onTestcodeChange: function (){

                this.model.set({
                    testCode: this.codeEditor.getSession().getValue()
                });
            },

            /**
             * 当浏览器选择发生变化
             */
            onRequestBrowsersChange: function (){

                var checkboxes = this.browserListEl.find( 'input[type=checkbox]' );
                var requestBrowsers = [];

                $( checkboxes).each(function (){

                    var checkbox = $( this );
                    var name;

                    if( checkbox.prop( 'checked' ) ){

                        name = checkbox.parent().attr( 'data-type' );

                        requestBrowsers.push( name );
                    }
                });

//                console.log( availableBrowsers );

                this.model.set({
                    requestBrowser: requestBrowsers
                });

            },

            onRunBtnClick: function (){

                this.model.set({
                    testCode: this.codeEditor.getSession().getValue()
                });

                // 检查是否正确
                var result = this.model.validation();

                if( result.result ){

                    this.hideAlert();
                    this.runTest();
                }
                else {

                    this.showAlert( result.msg );
                }

            }
        }),

        TestInfo: Backbone.View.extend({

            initialize: function (){

                this.testInfoList = {};
                this.nav = this.$( '.nav-tabs' );
            },

            events: {

            },

            addItem: function ( data ){

                this.testInfoList[ data.type ] = new Views.TestInfoItem({
                    data: data
                });
            },

            validation: function (){

                return this.model.validation();
            },

            removeItem: function ( type ){

                this.testInfoList[ type ].remove();
                delete this.testInfoList[ type ];
            },

            activeItem: function ( type ){

                this.testInfoList[ type ].triggerEl.click();
            },

            activeFirstIten: function (){

                var firstType = $( this.nav.children()[ 0 ]).attr( 'data-type' );

                this.activeItem( firstType );
            },

            clear: function (){

                _.each( this.testInfoList, function ( item ){

                    item.remove();
                });

                this.testInfoList = {};
            }
        }),

        TestInfoItem: Backbone.View.extend({

            initialize: function (){

                this.parentEl = $( '#output-tabs' );
                this.model = new Models.TestInfoItem( this.options.data );
                this.paneTplId = 'test-info-pane-tpl';
                this.triggerTplId = 'test-info-trigger-tpl';
                this.paneTpl = _.template( $( '#' + this.paneTplId).html() );
                this.triggerTpl = _.template( $( '#' + this.triggerTplId).html() );

                this.attachModel();
            },

            events: {

            },

            attachModel: function (){

                var that = this;

                this.model.on( 'change:stat', function ( m ){

                    var stat = m.get( 'stat' );

                    if( stat === 'finished' ){

                        that.render();
                    }
                    else if( stat === 'error' ){

                        that.error();
                    }
                    else {

                        that.testing();
                    }

                });
            },

            render: function (){

                var data = this.model.toJSON();

                if( this.triggerEl ){
                    this.triggerEl.remove();
                }
                if( this.paneEl ){
                    this.paneEl.remove();
                }

                this.triggerEl = $( this.triggerTpl( data ) );
                this.paneEl = $( this.paneTpl( data ) );

                this.parentEl.find( '.nav-tabs').append( this.triggerEl );
                this.parentEl.find( '.tab-content').append( this.paneEl );
            },

            error: function (){

            },

            testing: function (){

            },

            remove: function (){

                this.model.destroy();
                this.triggerEl.remove();
                this.paneEl.remove();
            }
        })
    };


    /*== models ==*/
    var Models = {

        TestInfo: Backbone.Model.extend({

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
        }),


        /**
         * 单个浏览器测试结果视图
         */
        TestInfoItem: Backbone.Model.extend({

            initialize: function (){

                var data = this.toJSON();

                this.fetch();

            },

            fetch: function (){

                var m = this.toJSON();
                var that = this;
                var data = {
                    type: m.type,
                    testcode: encodeURIComponent( m.testCode )
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
                var screenShot;
                var type;
                var _data = data.data;

                if( data.result ){

                    logs = _data.logs;
                    screenShot = _data.screen;
                    type = _data.type;

                    this.set({
                        stat: 'finished',
                        logs: logs,
                        screenshot: screenShot,
                        type: type
                    });
                }
                else {

                    this.set({
                        stat: 'error'
                    });
                }

            },

            defaults: {
                stat: 'testing', // testing | finished | error,
                defaultActive: false,
                testCode: '',
                screenshot: '',
                logs: '',
                type: 'browser'
            }
        })
    }

})();
