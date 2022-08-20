let Backend = {
    alertMessage: function (message) {
        $('.alert-message span').text(message);
        $(".alert-message").fadeIn("slow", function(){
            setTimeout(function(){
                $(".alert-message").fadeOut("slow");
            },2000);
        });
    }
}

$('body').on('click', '#apples-generate', function(e) {
    e.preventDefault();
    $.post('/apples/ajax-regenerate-random-apples', {}, function (data) {
        $(".apples__wrapper").html(data.html)
        Backend.alertMessage(data.message)
    }, 'json');
});

$('#fall-to-ground').on('click', function(e) {
    e.preventDefault();
    let data = { id: $(this).data('id')};
    $.post('/apples/ajax-fall-to-ground', data, function (data) {
        Backend.alertMessage(data.message)
    }, 'json');
});

$('.eat-apple-form').on('submit', function(e) {
    e.preventDefault();
    $.post('/apples/ajax-eat-apple', $(this).serialize(), function (data) {
        $(".apples__items").replaceWith(data.html)
        Backend.alertMessage(data.message)
    }, 'json');
});

$('.remove-apple').on('click', function(e) {
    e.preventDefault();
    let data = { id: $(this).data('id')};
    $.post('/apples/ajax-remove-apple', data, function (data) {
        $(".remove-apple[data-id='"+data.id+"']").parents('.apples__item').remove()
        Backend.alertMessage(data.message)
    }, 'json');
});