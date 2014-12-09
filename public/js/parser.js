$(document).ready(function(){
    //parse();
});

function parse(){
    var container = $('.result');
    var urlContainer = container.find('.url');

    $.ajax({
        url: "http://cometstudio.ru/",
        cache: false
    })
    .done(function( html ) {
            alert('done');
        urlContainer.html( html );
    });
}