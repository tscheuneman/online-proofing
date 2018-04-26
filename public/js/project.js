
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

function createMessageThread(proj_id, thread) {
    axios.post('/admin/message/thread', {
        thread_name: thread,
        project_id: proj_id
    })
        .then(function (response) {
            let returnData = response.data;
            if(returnData.status === "Success") {
                loadInThreads(proj_id);
            }
            else {
                alert(returnData.message);
            }
        })
        .catch(function (error) {
            console.log(error);
        });
}

function loadInThreads(proj_id) {
    $('#createThreadLabel').show();
    $('#createThreadMessage').hide();
    $('#messageBackButton').hide();

    $('#message_container').fadeOut(150, function() {
        $(this).empty();
        $('#message_loader').fadeIn(150, function() {
            repopulateThreads(proj_id);
        });
    });
}

function repopulateThreads(proj_id) {
    axios.get('/admin/message/thread/' + proj_id, {
    })
        .then(function (response) {
            let returnData = response.data;
            if(returnData.status === "Success") {
                let threadData = returnData.message;
                threadData.forEach(function(elm) {
                    let returnElm = '<div class="messageThread" data-name="'+elm.subject+'" data-id="'+elm.id+'">' +
                        elm.subject + '(' + elm.msg_cnt_count + ')' +
                        '</div>';
                    $('#message_container').append(returnElm);
                });
                $('#message_loader').fadeOut(150, function() {
                    $('#message_container').fadeIn(150, function() {

                    });
                });
            }
            else {
                alert(returnData.message);
            }
        })
        .catch(function (error) {
            console.log(error);
        });
}

function goToMessageThread(id, thread_name) {
    axios.get('/admin/message/' + id, {
    })
        .then(function (response) {
            let returnData = response.data;
            if(returnData.status === "Success") {
                let threadData = returnData.message;
                $('#message_container').fadeOut(150, function() {
                    $(this).empty();
                    $('#createThreadLabel').hide();
                    $('#createThreadMessage').show().data('thread', id);
                    $('#messageBackButton').show();
                    $('#message_loader').fadeIn(150, function() {
                        populateMessages(threadData, thread_name);
                    });
                });
            }
            else {
                alert(returnData.message);
            }
        })
        .catch(function (error) {
            console.log(error);
        });
}

function populateMessages(threadData, thread_name) {
    let mL = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
    let user = $('#this_user').val();
    if(threadData.length === 0) {
        let returnElm = '<p class="none">' +
            'No Messages in ' + thread_name
            '</p>';

        $('#message_container').append(returnElm);
    }
    else {
        threadData.forEach(function(elm) {
            let userClass = "";
            if(elm.user.id === user) {
                userClass = "belongs";
            }
            let createdAt = new Date(elm.created_at);
            let hours = createdAt.getHours();
            let post_fix = 'am';
                if(hours > 12) {
                    hours = hours - 12;
                    post_fix = 'pm';
                }
            let returnElm = '<div class="messageContentContainer '+userClass+'">' +
                '<div class="userInfo">'+elm.user.first_name+ ' ' + elm.user.last_name + ' | ' +
                mL[createdAt.getMonth()] + ' ' + createdAt.getDate() + ' ' + hours + ':' + createdAt.getMinutes() + ' ' + post_fix+
                '</div>' +
                '<div class="indivMessage">' +
                elm.message +
                '</div></div>';

            $('#message_container').append(returnElm);
        });
    }

    $('#message_loader').fadeOut(150, function() {
        $('#message_container').fadeIn(150, function() {

        });
    });

}

function showCreateMessage(elm) {
    $('.messageHolder').empty();
    let thread = $(elm).data('thread');
    let val = '<div class="dropdown-menu block" aria-labelledby="createThreadMessage">' +
        '<div class="px-3 py-3">' +
        '<div class="form-group">' +
        '<label for="threadName">Message</label>'+
        '<textarea class="form-control" id="mainMsg" cols="30" rows="10"></textarea>'+
        '<input type="hidden" id="threadID" value="'+thread+'">'+
        '</div>'+
        '<button id="addMessage"  type="submit" class="btn btn-primary">Create</button>'+
        '</div></div>';
    $('.messageHolder').append(val);
}
function createMessage(message, thread) {
    $('.messageHolder').empty();
    axios.post('/admin/message', {
        thread: thread,
        message: message
    })
        .then(function (response) {
            let returnData = response.data;
            if(returnData.status === "Success") {
                $('#message_container').fadeOut(150, function() {
                    $(this).empty();
                    $('#message_loader').fadeIn(150, function() {
                        goToMessageThread(thread, '');
                    });
                });
            }
            else {
                alert(returnData.message);
            }
        })
        .catch(function (error) {
            console.log(error);
        });
}
function submitRevision(imgs, id) {
    $('#loader').fadeIn(500, function() {
        axios.post('/project', {
            dataArray: JSON.stringify(imgs),
            projectID: id
        })
            .then(function (response) {
                let returnData = response.data;
                if(returnData.status === "Success") {
                    location.assign("/");
                }
                else {
                    alert(returnData.message);
                    $('#loader').fadeOut(500);
                }
            })
            .catch(function (error) {
                console.log(error);
            });
    });
}

function populateCanvas(array) {
    if(!array) {
        $('#loader').fadeOut(500);
        return false;
    }
    let img = null;
    let imageSize = null;
    let hideElm = null;
    window.items = [];
    window.bounds = [];

    let x = 0;
    array.data.forEach(function(elm) {
        imageSize = {width: elm.width, height: elm.height};
        window.bounds[x] = imageSize;
        window.items[x] = LC.init(
            document.getElementById('canvas_'+x),
            {
                imageSize: imageSize,
                imageURLPrefix: '../storage/icons',
                secondaryColor: 'transparent'
            },
        );
        img = new Image;
        img.src = array.linkAddy + '/' + elm.file;
        window.items[x].saveShape(LC.createShape('Rectangle', {x: 0, y: 0, width: elm.width, height: elm.height, strikeWidth: 0, strikeColor: '#000', fillColor: '#fff'}));
        window.items[x].saveShape(LC.createShape('Image', {x: 0, y: 0, image: img}));
        if(x > 0) {
            hideElm = $('#canvas_' + x).parent();
            $(hideElm).fadeOut(500);
        }
        x++;
    });
    $('#loader').fadeOut(500);
}

function approveRevision(id) {
    $('#loader').fadeIn(500, function() {
        axios.post('/project/approve', {
            projectID: id
        })
            .then(function (response) {
                console.log(response);
                let returnData = response.data;
                if(returnData.status === "Success") {
                    alert(returnData.message);
                    location.assign("/");
                }
                else {
                    alert(returnData.message);
                    $('#loader').fadeOut(500);
                }
            })
            .catch(function (error) {
                console.log(error);
            });
    });
}