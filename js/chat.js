function openForm() {
    document.getElementById("chatForm").style.display = "block";
}

function closeForm() {
    document.getElementById("chatForm").style.display = "none";
}

var current_chatter = 0;
var current_logged_user = 0;

//refresh chat
setInterval(function () {
    if (document.getElementById("chatForm").style.display != "none") {
        retrieveMessages()
    }
}, 5000);


//send message after enter
$(document).ready(function () {
    $("#msg").keyup(
        function (e) {

            if (e.keyCode === 13) {
                sendMessage()
            }
        }
    );
})


function sendMessage() {
    var sMessage = $("#msg").val();
    if ($.trim(sMessage) != '') {
        $.ajax({
            method: "POST",
            url: "apis-public/api-chat-update.php",
            data: {sMessage: sMessage},
            cache: false
        }).done(function (data) {
            console.log('GEA query result: ' + data)

            $('div#message-text').html(data)
            $('#msg').val('')

        }).fail(function () {
            console.log('FATAL ERROR')
        })
        return false


    }

}


function retrieveMessages() {
    $.ajax({
        method: "GET",
        url: "apis-public/api-chat-refresh.php",
        data: {},
        cache: false
    }).done(function (data) {
        console.log('GEA query result: ' + data)
        $('div#message-text').html(data)


    }).fail(function () {
        console.log('FATAL ERROR')
    })
    return false
}




function openPrivateForm(iUserId2, iUserId) {
    document.getElementById("privateChatForm").style.display = "block";
    current_chatter = iUserId2;
    console.log('chatter name1 is ' + iUserId2);
    current_logged_user = iUserId;
    retrievePrivateMessages(iUserId2);
    markPrivateMessagesAsRead(iUserId2);
   // $("label[for='private-msg']").empty();
   getUsernameForId(iUserId2);
    //console.log('chatter name is ' + sChatterName);
    //$("label[for='private-msg']").append(iUserId2);
}

function closePrivateForm() {
    document.getElementById("privateChatForm").style.display = "none";
}

//refresh private chat
setInterval(function () {
    if (document.getElementById("privateChatForm").style.display != "none") {
        retrievePrivateMessages(current_chatter, current_logged_user)
    }
    retrieveUnreadPrivateMessages() //displays buttons
}, 5000);


//send private message after enter
$(document).ready(function () {
    $("#private-msg").keyup(
        function (e) {

            if (e.keyCode === 13) {
                sendPrivateMessage()
            }
        }
    );
})


//onclick in textarea of private chat all messages from the chatter are marked as read
$(document).ready(function(){
    $('#private-msg').click(function() {

        var iUserId2 = $("#profile-owner-id").text();
        if(iUserId2 != current_logged_user){
            iUserId2 = current_chatter;
        }
        markPrivateMessagesAsRead(iUserId2);

    });

});





function sendPrivateMessage() {
    var sMessage = $("#private-msg").val();
    var iUserId2 = $("#profile-owner-id").text();
    if(iUserId2 != current_logged_user){
        iUserId2 = current_chatter;
    }
    console.log('The id of chatter is: ' + iUserId2)
    if ($.trim(sMessage) != '') {
        $.ajax({
            method: "POST",
            url: "apis-public/api-private-chat-update.php",
            data: {iUserId2: iUserId2, sMessage: sMessage},
            cache: false
        }).done(function (data) {
            console.log('GEA query result: ' + data)

            $('div#private-message-text').html(data)
            $('#private-msg').val('')

        }).fail(function () {
            console.log('FATAL ERROR')
        })
        return false


    }

}



function retrievePrivateMessages(iUserId2) {
    var iUserId2 = iUserId2;
    if (iUserId2 == current_logged_user) {
        iUserId2 = $("#profile-owner-id").text();
    }
    $.ajax({
        method: "GET",
        url: "apis-public/api-private-chat-refresh.php",
        data: {iUserId2:iUserId2},
        cache: false
    }).done(function (data) {
        console.log('GEA query result: ' + data)
        $('div#private-message-text').html(data)


    }).fail(function () {
        console.log('FATAL ERROR')
    })
    return false
}



function retrieveUnreadPrivateMessages() {
    $.ajax({
        method: "GET",
        url: "apis-public/api-get-recent-unread-private-messages.php",
        data: {},
        cache: false
    }).done(function (data) {
        console.log('GEA query result: ' + data)
        $('div#unread-buttons-block').html(data)


    }).fail(function () {
        console.log('FATAL ERROR')
    })
    return false
}


function markPrivateMessagesAsRead(iUserId2) {
    $.ajax({
        method: "GET",
        url: "apis-public/api-mark-private-messages-as-read.php",
        data: {iUserId2:iUserId2},
        cache: false
    }).done(function (data) {



    }).fail(function () {
        console.log('FATAL ERROR')
    })
    return false
}


function getUsernameForId(iUserId2) {
    $.ajax({
        method: "GET",
        url: "apis-public/api-get-username-for-id.php",
        data: {iUserId2:iUserId2},
        cache: false
    }).done(function (data) {
        console.log('username result: ' + data)
        //$("label[for='private-msg']").empty();
        $("label[for='private-msg']").text('Recent messages from ');
        $("label[for='private-msg']").append(data);

    }).fail(function () {
        console.log('FATAL ERROR')
    })
    //return false
}


