$('#frmSignup').submit(function () {

    $.ajax({
        method: "POST",
        url: "apis-public/api-signup",
        data: $('#frmSignup').serialize(),
        dataType: "JSON"
    }).done(function (jData) {
        console.log(jData)
        if (jData.status == 1) {
            swal({title: "CONGRATS", text: jData.message, icon: "success",});
            location.href = 'signup_success'

        } else {
            swal({
                title: "FAIL",
                text: jData.message,
                icon: "warning",
            });
        }

        return
    }).fail(function () {
        console.log('error')
    })


    return false
})