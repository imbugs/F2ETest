exports.run = function(client, response){
    client
        .init()
        .url("http://www.google.com")
//        .setValue("#lst-ib", "webdriver")
//        .submitForm("#tsf")
//        .getTitle(function(e){
//            response.write(e);
//        })
        .tests.titleEquals("Google", "Title of the page is 'Google'")
//        .saveScreenshot("1.jpg",function(e){
//            console.log( '123' );
//            console.log( e );
//        })
        .end(function(){
            response.end();
        });

};
