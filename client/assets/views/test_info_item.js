define( [ '../config', '../models/test_info_item' ], function( cfg, testInfoItemModel ){

    var views = cfg.views;
    var testInfoItemView = views[ 'testInfoItem' ] = Backbone.View.extend({

        initialize: function (){

            this.parentEl = $( '#output-tabs' );
            this.model = new testInfoItemModel( this.options.data );
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
            if(!data.result){
                alert(data.error);
                return;
            }
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

            // 测试结果容器
            this.testResultWrap = this.parentEl.find( '#test-result-wrap' );

            this.testResultWrap.find( '#test-suite-list').html( this.renderTestResult() );

            // 初始化测试结果部分的折叠逻辑
            this.attachTestResult();
        },

        /**
         * 渲染测试结果列表
         * @return {*}
         */
        renderTestResult: function (){

            var data = this.model.toJSON();
            var testResult = data.testResult;
            var html;

            html = this._renderTestResult( testResult.list );

            return html;
        },

        _renderTestResult: function ( testResult ){

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

        /**
         * 为解释结果列表添加折叠逻辑
         */
        attachTestResult: function (){

            this.testResultWrap.delegate( '.title i', 'click', function (){
                $( this ).toggleClass( 'icon-plus' );
                $( this ).toggleClass( 'icon-minus' );

                $( $( this ).parents( 'li' )[ 0 ] ).toggleClass( 'fold' );
            });

        },

        // 若出错 也render，信息已经在data中 模板会进行逻辑判断 显示错误
        error: function (){

            this.render();
        },

        remove: function (){

            this.model.destroy();
            this.triggerEl && this.triggerEl.remove();
            this.paneEl && this.paneEl.remove();
        }
    });

    return testInfoItemView;

});