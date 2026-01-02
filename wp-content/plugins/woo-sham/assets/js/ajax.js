jQuery(document).ready(function($){
    $('#currency_selector').submit(function(e){
        e.preventDefault();
        var nonce = set_currency_cookie_ajax.ajax_nonce;
        var currency = $("#select_currency").val();
        jQuery.ajax({
            type: 'post',
            dataType: 'json',
            url: set_currency_cookie_ajax.ajaxurl,
            data: {action: 'set_currency_cookie', currency_selector: currency, nonce: nonce},
            success: function (response){
                if(response && response.type == "success"){
                    location.reload(true);
                }
            },
            error: function(xhr, status, error) {
                console.error('AJAX Error:', error);
                location.reload(true);
            }
        });
    });

});
