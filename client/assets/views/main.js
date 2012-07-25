define( [ '../config', './test_info', '../models/test_info' ], function( cfg, testInfoView, testInfoModel ){

    var views = cfg.views;
    var API = cfg.API;

    var mainView = views[ 'main' ] = Backbone.View.extend({

        initialize: function (){

            this.browserListEl = $( '#browsers' );
//                this.codeTextarea = $( '#testcode-area' );

            this.codeEditorInit();
            this.model = new testInfoModel();
            this.TestInfo = new testInfoView({ el: $( '#output-wrap')});
            this.alertEl = this.$( '.input-error').hide();
            this.runBtn = this.$( '#run-test-btn' );

            $("button.browser").button('loading');
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
            'click #browsers button.browser': 'onRequestBrowsersChange',
            'click #run-test-btn': 'onRunBtnClick',
            'click #add-email-btn': 'addEmailBtnClick',
            'click .J_Del_Job': 'delJobBtnClick',
            'click #view-jobs-btn': 'viewJobsBtnClick',
            'click .browser-info .browser-stat-refresh': 'onBrowserStatRefresh'
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
                that.showFinish();
                that.runBtn.removeClass( 'disabled' );
                that.codeEditor.setReadOnly( false );
                that.TestInfo.show();
            });
        },
        //显示 所有测试已完毕 状态
        showFinish: function(){
            var msg =  '所有测试完毕! <a href="?testFile={{script}}">再次运行</a>  ' +
                       '| <a href="' + API.MULT_REQUEST_TEST + '?testFile={{script}}&types={{types}}" target="_blank">JSON结果</a> ' +
                       '| <a href="#addEmailModal" data-toggle="modal"  title="每天凌晨会定时检查，并邮件结果给您">定时任务</a> ';
            try{
                msg = msg.replace(/{{script}}/g, main.curData.script);
                msg = msg.replace(/{{types}}/g, main.model.attributes.requestBrowser.join('|'));
                this.showAlert(msg, 'success' );
            }catch(e){}this.showAlert(msg, 'success' );
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

                browserSpan.button('reset');
            }
            else {

                browserSpan.button('loading');
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
            var self = this;
            setTimeout(function(){
                var checkboxes = self.browserListEl.find( 'button.browser' );
                var requestBrowsers = [];

                $( checkboxes).each(function (){

                    var checkbox = $( this );
                    var name;

                    if( checkbox.hasClass( 'active' ) ){

                        name = checkbox.attr('data-type');

                        requestBrowsers.push( name );
                    }
                });

//                console.log( availableBrowsers );

                self.model.set({
                    requestBrowser: requestBrowsers
                });
            }, 0);
        },
        addEmailBtnClick: function(ev){
            var emails = $('#job-emails').val(),
                title = $('#job-title').val();
            var script;
            try{
                script  = main.curData.script;
            }catch(e){
                script = 'none';
            }
            if(!emails){
                return;
            }
            var reqURI = API.DO_MAIL_LIST + '?action=add&emails=' + emails + '&script=' +
                encodeURIComponent(API.MULT_REQUEST_TEST + '?testFile=' + script + '&types=' + main.model.attributes.requestBrowser.join('|')) +
                '&title=' + title;
            $.ajax(reqURI).done(function(data){
                $('#addEmailModal').modal('hide');
            });
        },
        viewJobsBtnClick: function(ev){
            $.ajax(API.DO_MAIL_LIST,{dataType: 'jsonp'}).done(function(data){
                debugger
                var jobs = $.isArray(data.job) ? data.job : [data.job],
                    job,
                    trs = '';
                while(job = jobs.pop()){
                    trs +='<tr>';
                    trs += '<td>'+job["@attributes"]['title']+'</td>';
                    trs += '<td>'+job.email+'</td>';
                    trs += '<td><a href="'+ job["@attributes"]['script'].replace('server/API/multiTestJson.php', 'client/index.php') +'" class="btn btn-mini" target="_blank">查看</a>&nbsp;<a href="#" data-job-script="'+ job["@attributes"]['script'] +'" class="btn btn-mini btn-danger J_Del_Job" target="_blank">删除</a></td>';
                    trs +='</tr>';
                }
                $('#viewJobsModal tbody').html(trs);
                $('#viewJobsModal').modal('show');
            });
            return false;
        },
        delJobBtnClick: function(ev){
            if(!confirm('确定要删除？'))return false;

            var reqURI = API.DO_MAIL_LIST + '?action=del&script=' + encodeURIComponent($(ev.currentTarget).attr('data-job-script'));
            $.ajax(reqURI,{dataType: 'jsonp'});
            $(ev.currentTarget).parent().parent().fadeOut(500).remove();
            return false;
        },

        /**
         * 当浏览器状态更新按钮被按下
         */
        onBrowserStatRefresh: function(){

            this.model.getBrowserStat();
        },

        onRunBtnClick: function (ev){
            if(this.runBtn.hasClass('disabled')){
                return;
            }
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
    });

    return mainView;
});