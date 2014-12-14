var cookieExpires = 7;

$(document).ready(function(){

    fixMMenuvisibility();

    $(window).on('resize', function(){ fixMMenuvisibility(); });

    initExpandMenuBtn();

    initNotification();

    initCartAlert();
});

function initExpandMenuBtn(){
    var btn = $('.header .user-controls-reverse');
    var expandable = $('.menu');
    var expand;

    btn.on(
        'click',
        function()
        {
            expand = expandable.css('display') == 'none';
            expandContainer(expandable, expand);
        }
    );
}

function fixMMenuvisibility()
{
    var expandable = $('.menu');

    var width = $(document).width();

    if(width < 800){
        expandContainer(expandable, false, true);
    }else{
        expandContainer(expandable, true);
    }
}

function initNotification(){
    var cookieName = 'notificationCollapsed';
    var expandable = $('.notification');
    var btn = expandable.find('.close');

    if(cookie(cookieName)) {
        expandable.hide();
    }else if(typeof(notificationOpened) != 'undefined'){
        expandable.show();
    }

    btn.on(
        'click',
        function()
        {
            expandContainer(expandable, false);
            cookie(cookieName, 1);
        }
    );
}


function signup()
{
    var container = $('.forms.login');
    var form = container.find('form');

    var options = {
        success: function (response){
            if(response.message){
                alert(response.message);
            }else if(response.uri){
                document.location.assign(response.uri);
            }else if(response.html){

            }
        },
        url: '/signup/do/',
        dataType:  'json'
    };

    form.ajaxSubmit(options);

    return false;
}

function login()
{
    var container = $('.forms.login');
    var form = container.find('form');

    var options = {
        success: function (response){
            if(response.message){
                alert(response.message);
            }else if(response.location){
                document.location.assign(response.location);
            }else if(response.html){

            }
        },
        dataType:  'json'
    };

    form.ajaxSubmit(options);

    return false;
}

function initCartAlert()
{
    var cart = cookie('cart');

    if(typeof (cart) != 'undefined') {
        var count = cart.split(',').length;

        if (count > 0) {
            var alerts = $('.user-controls .cart');
            alerts.text(count);
            alerts.show();
        }
    }

    setCartSummary();

    initSummaryAlerts();
}

function initSummaryAlerts()
{
    var sum = 0;

    if(typeof (cartCount) != 'undefined') sum += Math.round(cartCount);
    if(typeof (ordersCount) != 'undefined') sum += Math.round(ordersCount);

    if(sum > 0) {
        var alert = $('.user-controls-reverse .alert');
        alert.text(sum);
        alert.show();
    }
}

function setCartSummary(){
    var container = $('.cart');
    var items = container.find('.scope .items');
    var summaryContainer = container.find('.summary');
    var price, amount, summary = 0;

    if(summaryContainer.length && items.length){
        items.each(function(){
            price = Math.round($(this).find('.price').text());
            amount = Math.round($(this).find('.amount .value').text());
            summary += price*amount;
        });

        summaryContainer.text(summary);
    }
}

function cartAdd(id, button)
{
    button = $(button);

    toggleState(button);

    $.post('/cart/add', {
        id: id
    }, function(response){
        if(response.message){
            alert(response.message);

            toggleState(button);
        }else if(response.html){
            button.html(response.html);
            if(response.location) {
                button.removeAttr('onclick');
                button.prop('href', response.location);
            }

            if(response.cartCount) cartCount = response.cartCount;

            initCartAlert();
        }

    }, 'json');

    return false;
}

function manageCart(id, amount, button)
{
    var container = $('.cart .scope');
    var itemContainer = container.find('#id'+id);
    var amountValueContainer = itemContainer.find('.amount .value');

    var value = Math.round(amountValueContainer.text());
    var newValue = value + Math.round(amount);

    //alert(newValue);

    button = $(button);
    var icon = button.find('i');
    wait(icon);

    $.post('/cart/manage', {
        id: id,
        amount: amount
    }, function(response){
        if(response.message){
            alert(response.message);
        }else if(response.location){
            document.location.assign(response.location);
        }else{
            if(!newValue || (amount == 0)){
                itemContainer.slideUp('fast');
            }else{
                amountValueContainer.text(newValue);
            }

            if(response.cartCount) cartCount = response.cartCount;

            initCartAlert();
        }

        release(icon);
    }, 'json');

    return false;
}

function submitCart(){
    var container = $('.order-form');
    var form = container.find('form');
    var button = form.find('button');

    toggleState(button);

    var options = {
        success: function (response){
            if(response.message){
                alert(response.message);
            }
            if(response.location){
                document.location.assign(response.location);
            }

            toggleState(button);
        },
        url: '/cart/submit',
        dataType:  'json'
    };

    form.ajaxSubmit(options);

    return false;
}

function wait(el){
    el.addClass('fa-spinner');
}

function release(el){
    el.removeClass('fa-spinner');
}

function toggleState(objs){
    var state, altState;

    if(typeof(swap) == 'undefined') var swap = true;

    objs.each(function(){
        state = $(this).html();
        altState = $(this).attr('opt');

        if(swap) $(this).attr('opt', state);
        if(altState) $(this).html(altState);
    });
}

function expandContainer(container, expand, fast)
{
    if(typeof (fast) != 'undefined') fast = 0; else fast = 500;

    if(expand){
        container.slideDown(fast, 'easeOutQuint');
    }else{
        container.slideUp(fast, 'easeOutQuint');
    }
}

function cookie(name, value)
{
    var valueType = typeof (value);

    if(valueType != 'undefined'){
        if(valueType != 'null'){
            return $.cookie(name, value, { expires: cookieExpires, path: '/' });
        }else{
            return $.removeCookie(name, value, { path: '/' });
        }

    }else{
        return $.cookie(name);
    }
}