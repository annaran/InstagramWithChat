//displays dropdown
$(document).ready(function () {


        $('#searchword').keyup(function (e) {


                if (e.key === "Escape") {
                    $('#search-result').hide();
                } else {


                    var tag = $(this).val();
                    if (tag.length > 0) {
                        searchWord(tag);
                    } else {
                        $('#search-result').hide();
                    }


                }

            }
        );


    }
);


//searches for tag in db
function searchWord(searchword) {
    $('#search-result').show();
    $.post('apis-public/api-search.php', {
            'searchword': searchword
        },
        function (data) {
            if (data != "")
                $('#search-result').html(data);
        }
    ).fail(function () {
            console.log('FATAL ERROR')
        }
    );

}