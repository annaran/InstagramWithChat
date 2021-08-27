$('#frmLogin').submit(function () {

    $.ajax({
        method: "POST",
        url: "apis-public/api-login",
        data: $('#frmLogin').serialize(),
        dataType: 'JSON'
    }).done(function (jData) {
        if (jData.status == 0) {
            console.log(jData)
            swal({title: "WRONG CREDENTIALS", text: jData.message, icon: "warning",});
            return
        }

        location.href = 'profile'

    }).fail(function () {
        console.log('error')

    })

    return false

})