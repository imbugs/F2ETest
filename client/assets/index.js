(function(){

    var API = {

//        BROWSER_STAT: 'http://f2etest/client/fake/stat.php',
        REQUEST_TEST: 'http://f2etest/client/fake/test.php',

        BROWSER_STAT: '../server/API/serverStatus.php'
//        REQUEST_TEST: '../server/API/doRequest.php'
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
                this.TestInfo = new Views.TestInfo({ el: $( '#output-wrap')});
                this.alertEl = this.$( '.input-error').hide();
                this.runBtn = this.$( '#run-test-btn' );

                this.TestInfo.hide();
                this.attachModel();
                this.attach();
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

            attach: function (){

                var that = this;
                $( this.TestInfo ).bind( 'testFinished', function (){

                    that.showAlert( '所有测试完毕!', 'success' );
                    that.runBtn.removeClass( 'disabled' );
                    that.codeEditor.setReadOnly( false );
                    that.TestInfo.show();
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

            showAlert: function( msg, type ){

                type = type || '';

                this.alertEl.removeClass( 'alert-error' );
                this.alertEl.removeClass( 'alert-success' );
                this.alertEl.removeClass( 'alert-info' );

                if( type !== '' ){

                    this.alertEl.addClass( 'alert-' + type );
                }
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
                    this.showAlert( '测试进行中，请耐心等待...' );
                    this.runBtn.addClass( 'disabled' );
                    this.codeEditor.setReadOnly( true );
                    this.TestInfo.hide();

                }
                else {

                    this.showAlert( result.msg, 'error' );
                }

            }
        }),

        TestInfo: Backbone.View.extend({

            filterClsPrefix: 'log-list-',
            filterTypes: [ 'command', 'data', 'result', 'error', 'custom', 'screenshotSave' ],

            initialize: function (){

                this.testInfoList = {};
                this.nav = this.$( '.nav-tabs' );
                this.pane = this.$( '.bd' );
                this.filterList = this.$( '.log-filter-list' );
                this.filterInit();
            },

            hide: function (){

                $( this.el ).hide();
            },

            show: function (){

                $( this.el ).show();
            },

            // 初始化filter按钮们
            filterInit: function (){

                var that = this;

                this.filterList.children().each( function (){

                    var item = $( this );

                    // 默认全部打开获取隐藏
                    //item.addClass( item.attr( 'data-label' ) );

                    item.bind( 'click', function (){

                        item.toggleClass( item.attr( 'data-label' ) );
                        that.refreshFilterList();
                    });
                });

                // 先去掉所有的filter
                _.each( this.filterTypes, function ( type ){

//                    that.pane.addClass( that.filterClsPrefix + type );
                });

            },

            /**
             * 根据filter按钮的状态 更新列表
             */
            refreshFilterList: function (){

                var avaliableType = [];
                var that = this;

                this.filterList.children().each( function (){

                    var item = $( this );
                    var label = item.attr( 'data-label' );
                    var type;

                    if( item.hasClass( label ) ){

                        type = item.attr( 'data-type' );
                        avaliableType.push( type );
                    }
                });

                // 先去掉所有的filter
                _.each( this.filterTypes, function ( type ){

                    that.pane.removeClass( that.filterClsPrefix + type );

                });

                // 添加显示的filter
                _.each( avaliableType, function ( type ){
                    that.pane.addClass( that.filterClsPrefix + type );
                });
            },

            addItem: function ( data ){

                var newItem = new Views.TestInfoItem({
                    data: data
                });
                var that = this;

                this.testInfoList[ data.type ] = newItem;

                console.log( 'new', newItem );
                $( newItem ).bind( 'testFinished', function (){

                    that.checkIfAllFinished();
                });
            },

            /**
             * 检查是否所有的测试都已经完毕
             */
            checkIfAllFinished: function (){

                var itemType;
                var item;
                var finished = true;

                for( itemType in this.testInfoList ){

                    var stat;
                    item = this.testInfoList[ itemType ];
                    stat = item.model.get( 'stat' );

                    if( stat !== 'error' && stat !== 'finished' ){

                        finished = false;
                        return;
                    }
                }

                if( finished ){
                    $( this ).trigger( 'testFinished' );
                }
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
                this.testResultTplId = 'test-result-suite-tpl';
                this.testResultTpl = _.template( $( '#' + this.testResultTplId).html() );
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
                        console.log( 'testInfoItem', that );
                        $( that ).trigger( 'testFinished' );
                    }
                    else if( stat === 'error' ){

                        that.error();
                        $( that ).trigger( 'testFinished' );
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

                this.paneEl.find( '#test-suite-list').html( this.renderTestResult() );
            },

            renderTestResult: function (){

                var data = this.model.toJSON();
                var testResult = data.testResult;
                var html;

                html = this._renderTestResult( testResult );

                return html;
            },

            _renderTestResult: function ( testResult ){

                debugger;
                var i;
                var suite;
                var html;
                var childHtml;

                html = this.testResultTpl( { testResult: testResult } );

                for( i = 0; suite = testResult[ i ]; i++ ){

                    if( suite.suites.length > 0 ){

                        childHtml = arguments.callee.call( this, suite.suites );

                        html = html.replace( '##test-suite-list-tag-' + i + '##', childHtml );
                    }
                }

                return html;
            },

            // 若出错 也render，信息已经在data中 模板会进行逻辑判断 显示错误
            error: function (){

                this.render();
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
        })
    }

})();
