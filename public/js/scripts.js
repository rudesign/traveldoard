$(document).ready(function(){

});

function showFlights(){

    var container = $('.flights.form');
    var form = container.find('form');
    var resultContainer = container.find('.result');

    var options = {
        success: function (response){

            if(response.message){
                alert(response.message);
            }else if(response.uri){
                document.location.assign(response.uri);
            }else if(response.html){
                resultContainer.html(response.html);
            }
        },
        url: '/sabre/show/flights',
        dataType:  'json'
    };

    form.ajaxSubmit(options);

    return false;
}

function showFlightsSource(){

    var container = $('.flights.form');
    var form = container.find('form');
    var resultContainer = container.find('.result');

    var options = {
        success: function (response){

            if(response.message){
                alert(response.message);
            }else if(response.uri){
                document.location.assign(response.uri);
            }else if(response.html){
                resultContainer.html(response.html);

                initUlHighlight('.flights ul');
            }
        },
        url: '/sabre/source/flights',
        dataType:  'json'
    };

    form.ajaxSubmit(options);

    return false;
}

function initUlHighlight(el){
    $(el).bind('mouseover', function(){
        $(this).addClass('active');
    });
}