var TSParams = TSParams || {};
!function($) {
    $('.ticket-search-form').on('submit', function() {
        alert($(this).serialize());
        $.ajax({
            url: TSParams.url,
            type: 'POST',
            dataType: 'json',
            data: $('.ticket-search-form').serialize(),
            success: processResponse
        });
        return false;
    });
    var processResponse = function(response) {
        for(i in response)
            alert(response[i]);
    }
}(window.jQuery);
