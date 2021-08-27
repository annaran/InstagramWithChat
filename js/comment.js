function addComment(imageId) {
    var comment = $('input#comment').val();

    $.ajax({
        type: "POST",
        url: 'apis-public/api-add-comment',
        data: {
            "comment": comment,
            "imageId": imageId
        }
    }).done(function (data) {

        $('div#comment-text').html(data);
        $('input#comment').val('');

    }).fail(function () {
        console.log('FATAL ERROR')
    })

    return false
}
