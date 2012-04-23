exports.run = function(client, res){
    client
        .init()
        .url("http://www.google.com")
        .setValue("#lst-ib", "webdriver")
        .submitForm("#tsf")
        .getTitle(function(e){
            res.write(e);
        })
        .tests.titleEquals("Google", "Title of the page is 'Google'")
        .saveScreenshot()
        .end(function(a){
            res.write(JSON.stringify(a));
            console.log( arguments );
            res.end();
        });

};
