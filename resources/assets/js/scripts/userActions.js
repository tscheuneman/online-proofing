function submitRevision(imgs, id) {
    $('#loader').fadeIn(500, function() {

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
                secondaryColor: 'transparent',
                tools: [
                    LC.tools.Rectangle,
                    LC.tools.Polygon,
                ],
                strokeWidths: [
                    2,
                    5
                ]
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