var TSParams = TSParams || {};
var processResponse = function(response) {
    if (response && response.count) {
        $.each(response.tickets, function(i, ticket) {
            alert(ticket.total);
        });
    } else {
        $('#ticket-search-result').html('<div class="alert alert-info">Извините по Вашему запросу билтов не найдено</div>');
    }
};
