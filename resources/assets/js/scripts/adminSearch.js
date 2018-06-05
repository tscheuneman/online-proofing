window.searchProjects = function(inputVal){
    let theURL = '/admin/search/projects?q=' + inputVal;
    $.ajax(theURL)
        .done(function(data) {
            populateSearch(data);
            $('.loader').hide();
        })
        .fail(function() {
            alert( "error" );
        });
};
function populateSearch(data) {
    $('.searchResults').empty();
    if(data.length > 0) {
        data.forEach(function(elm) {
            let image = getImage(elm);
            console.log(elm);
            let returnData = '<a class="linkResult" href="/proof/admin/project/'+elm.file_path+'">' +
                '<div class="result">' +
                '<div class="image">' +
                image +
                '</div>'+
                '<p class="title">'+elm.order_name.job_id+' | <strong>' +elm.project_name+'</strong></p>' +
                '<span class="status">'+getStatus(elm)+'</span>'+
                '<br>'+
                '<strong>Customer(s): </strong>'+ getCustomers(elm.order_name.users) +
                '<br>'+
                '<strong>Project Manager(s): </strong>'+ getAdmins(elm.order_name.admins) +
            '</div></a>';

            $('.searchResults').append(returnData);
        });
        $('.searchResults').fadeIn(500);
    }
    return false;
}
function getStatus(elm) {
    if(elm.active) {
        if(elm.completed) {
            return "Approved";
        }
        return "Active";
    }
    else {
        if(elm.completed) {
            return "Closed Out";
        }
        return "Pending";
    }
}
function getCustomers(elm) {
    let returnArray = '';
    if(!Array.isArray(elm) || !elm.length) {
        return '';
    }
    elm.forEach(function(el) {
        if(returnArray !== '') {
            returnArray += ',';
        }
        returnArray += el.search_user.first_name + ' ' + el.search_user.last_name;
    });

    return returnArray;
}

function getAdmins(elm) {
    let returnArray = '';
    if(!Array.isArray(elm) || !elm.length) {
        return '';
    }
    elm.forEach(function(el) {
        if(returnArray !== '') {
            returnArray += ',';
        }
        returnArray += el.admin.search_user.first_name + ' ' + el.admin.search_user.last_name;
    });

    return returnArray;
}
function getImage(elm) {
    if(!Array.isArray(elm.inital_image) || !elm.inital_image.length) {
        return '';
    }
    let mL = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
    let fileInfo = JSON.parse(elm.inital_image[0].files);
    let createdAt = new Date(elm.created_at);

    console.log(fileInfo);

    return '<img src="/storage/projects/'+createdAt.getFullYear()+'/'+mL[createdAt.getMonth()]+'/'+elm.file_path+'/'+elm.inital_image[0].path+'/images/thumb_00.png" />';
}
function messageToggle() {
    let messageElm = $('#messages');
    if(!messageElm.hasClass('active')) {
        messageElm.animate({
            width: "30%",
        }, 500, function() {
            $('.messageOverallContainer').fadeIn(250);
            messageElm.addClass('active');
        });
    }
    else {
        $('.messageOverallContainer').fadeOut(250, function() {
            messageElm.animate({
                width: "0px",
            }, 500);
            messageElm.removeClass('active');
        });
    }
}