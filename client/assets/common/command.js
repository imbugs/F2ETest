define( [ '../config' ], function( cfg ){
    
    var API = cfg.API;
    
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

    return command;

});