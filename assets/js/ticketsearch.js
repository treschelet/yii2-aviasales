var TSParams = TSParams || {};
!function($) {
    $('.ticket-search-form').on('submit', function() {
        $.ajax({
            url: TSParams.url,
            type: 'POST',
            dataType: 'json',
            success: processResponse
        });
        return false;
    });
    var processResponse = function(response) {
        for(i in response)
            alert(response[i]);
    }
}(window.jQuery);
