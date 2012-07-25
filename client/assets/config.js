define(function(){

    return {
        API: {

    //        BROWSER_STAT: 'http://f2etest/client/fake/stat.php',
    //        REQUEST_TEST: 'http://f2etest/client/fake/test.php',

            BROWSER_STAT: 'http://'+ location.hostname + '/server/API/serverStatus.php',
            REQUEST_TEST: 'http://'+ location.hostname + '/server/API/doRequest.php',
            DO_MAIL_LIST: 'http://'+ location.hostname + '/server/API/doMailList.php',
            MULT_REQUEST_TEST: 'http://'+ location.hostname + '/server/API/multiTestJson.php'
        },
        views: {},
        models: {}
    }
});