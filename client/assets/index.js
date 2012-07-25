// 解决ace在加载时提示 text 模块找不到的bug
require.config({
    paths: {
        "text": "lib/ace/lib/ace/requirejs/text"
    }
});

define( [ 
    './config',
    'lib/underscore-min', 
    'lib/jquery-1.7.1.min', 
    'lib/backbone-min', 
    'lib/bootstrap/js/bootstrap.min', 
    'lib/ace/build/src/ace', 
    'views/main' ], function( cfg ){

    $( document).ready(function (){

        var Main = new cfg.views.main({
            el: $( '#content' )
        });

        window[ 'main' ] = Main;
        //混乱的代码，为了获取每次测试的原始数据,每次fetch data都会更新这个值
        window[ 'main' ]['curData'] = '';
    });

});
