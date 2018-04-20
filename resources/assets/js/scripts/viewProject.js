
function goNextItemProjectView() {
    let currEntry = $('div.entry.active');
    let maxElm = currEntry.data('numelm');
    let currImg = $('div.image.active', currEntry);

    let currImgNum = currImg.data('num');
    let nextImg = currImgNum + 1;
    if(parseInt(nextImg) >= parseInt(maxElm)) {
        nextImg = 0;
    }

    currImg.stop().fadeOut(200, function() {
        $(this).removeClass('active');
        $('div.image.proj_' + nextImg, currEntry).stop().fadeIn(200, function() {
            $(this).addClass('active');
            updatePageCount();
        });
    });
}
function goToElementFromPageComment(id) {
    let currEntry = $('div.entry.active');
    let currImg = $('div.image.active', currEntry);
    currImg.stop().fadeOut(200, function() {
        $(this).removeClass('active');
        $('div.image.proj_' + id, currEntry).stop().fadeIn(200, function() {
            $(this).addClass('active');
            updatePageCount();
        });
    });

}
function goPreviousItemProjectView() {
    let currEntry = $('div.entry.active');
    let maxElm = parseInt(currEntry.data('numelm')) - 1;
    let currImg = $('div.image.active', currEntry);

    let currImgNum = currImg.data('num');
    let nextImg = currImgNum - 1;
    if(parseInt(nextImg) < 0) {
        nextImg = maxElm;
    }

    currImg.stop().fadeOut(200, function() {
        $(this).removeClass('active');
        $('div.image.proj_' + nextImg, currEntry).stop().fadeIn(200, function() {
            $(this).addClass('active');
            updatePageCount();
        });
    });
}

function updatePageCount() {
    let currEntry = $('div.entry.active');
    let maxElm = parseInt(currEntry.data('numelm'));
    let currImg = $('div.image.active', currEntry);
    let currImgNum = parseInt(currImg.data('num')) + 1;

    $('div.counter span.current').html(currImgNum);
    $('div.counter span.max').html(maxElm);
}

function goToEntryItemById(id) {
    $('.entry.active').stop().fadeOut(500, function() {
        $(this).removeClass('active');

        $('.entry ').each(function() {
            let currentVal = $(this).data('id');
            if(currentVal === id) {
                $(this).stop().fadeIn(500, function() {
                    $(this).addClass('active');
                    updatePageCount();
                });
            }
        });
    });

    $('.commentContainer.active').stop().fadeOut(500, function() {
        $(this).removeClass('active');
        $('.commentContainer ').each(function () {
            let currentVal = $(this).data('id');
            if (currentVal === id) {
                $(this).stop().fadeIn(500, function () {
                    $(this).addClass('active');
                });
            }
        });
    });
}

function getLinkValue(val, proj) {
    axios.post('/admin/project/link', {
        val: val,
        project_id: proj
    })
        .then(function (response) {
            let returnData = response.data;
            if(returnData.status === "Success") {
               location.assign(returnData.message);
            }
            else {
                alert(returnData.message);
                $('#loader').fadeOut(500);
            }
        })
        .catch(function (error) {
            console.log(error);
        });
}