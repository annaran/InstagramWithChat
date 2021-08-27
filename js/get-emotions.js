//updates emotions in db
function setEmotion2(e, sEmotion, iEmotionId, iEmotions) {
    let oParent = e.parentElement
    let aSvgs = oParent.querySelectorAll('svg')
    let sImageId = oParent.getAttribute('data-image-id')


    $.ajax({
        method: "POST",
        url: "apis-public/api-set-emotions.php",
        data: {
            "emotion": sEmotion,
            "imageId": sImageId
        },
        dataType: "JSON",
        cache: false
    }).done(function (jData) {
        if (jData.status == 0) {
            console.log(jData)
            swal({title: "WRONG CREDENTIALS", text: jData.message, icon: "warning",});

            return
        }


    }).fail(function () {

    })

    getEmotionsAll(sImageId)
    getEmotionColor(e, sImageId)


}


//----------------------------------------------------------------------------
//refreshes totals for specified picture
function getEmotionsAll(imageId) {
    $.ajax({
        method: "GET",
        url: "apis-public/api-get-emotions-all.php",
        data: {imageId: imageId},
        dataType: "JSON",
        cache: false
    }).done(function (ajData) {
        console.log('GEA query result: ' + ajData)
        for (var i = 0; i < ajData.length; i++) {
            var sImageId = ajData[i].image_id
            console.log('image id is ' + sImageId)

            var sTotalLove = ajData[i].number_of_loves
            var sTotalLike = ajData[i].number_of_likes
            var sTotalDislike = ajData[i].number_of_dislikes
            var sTotalPoop = ajData[i].number_of_poops

            //set total no of emotions based on query
            $('[data-image-id=' + sImageId + ']').find('.' + 3).text(sTotalLove)
            $('[data-image-id=' + sImageId + ']').find('.' + 2).text(sTotalLike)
            $('[data-image-id=' + sImageId + ']').find('.' + 1).text(sTotalDislike)
            $('[data-image-id=' + sImageId + ']').find('.' + 0).text(sTotalPoop)

            console.log('emotion 3 refreshed for image ' + sImageId + ', new total is ' + sTotalLove)
            console.log('emotion 2 refreshed for image ' + sImageId + ', new total is ' + sTotalLike)
            console.log('emotion 1 refreshed for image ' + sImageId + ', new total is ' + sTotalDislike)
            console.log('emotion 0 refreshed for image ' + sImageId + ', new total is ' + sTotalPoop)

        }
    }).fail(function () {

    })
}


//--------------------------------------------------------------------------------------

//refreshes colors for each emotion
function getEmotionColor(e, imageId) {
    let oParent = e.parentElement
    let aSvgs = oParent.querySelectorAll('svg')

    $.ajax({
        method: "GET",
        url: "apis-public/api-get-emotion-color.php",
        data: {imageId: imageId},
        dataType: "JSON",
        cache: false
    }).done(function (ajData) {
        console.log('GEA query result: ' + ajData)

        if (ajData.length == 0) {
            //set color if user has no reaction to picture recorded in db
            aSvgs[0].style.fill = "black"
            aSvgs[1].style.fill = "black"
            aSvgs[2].style.fill = "black"
            aSvgs[3].style.fill = "black"
        } else {
            for (var i = 0; i < ajData.length; i++) {

                var sColorLove = ajData[i].color_of_loves
                var sColorLike = ajData[i].color_of_likes
                var sColorDislike = ajData[i].color_of_dislikes
                var sColorPoop = ajData[i].color_of_poops

                //set color based on query result
                aSvgs[0].style.fill = sColorLove
                aSvgs[1].style.fill = sColorLike
                aSvgs[2].style.fill = sColorDislike
                aSvgs[3].style.fill = sColorPoop

            }
        }


    }).fail(function () {

    })
}


//--------------------------------------------------------------------------------------



