define( [ '../config', './test_info_item' ], function( cfg, testInfoItemView ){

    var views = cfg.views;

    var testInfoView = views[ 'testInfo' ] = Backbone.View.extend({

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

            var newItem = new testInfoItemView({
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
    });

    return testInfoView;
});