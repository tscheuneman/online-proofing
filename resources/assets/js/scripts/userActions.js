function submitRevision(imgs, id) {
    $('#loader').fadeIn(500, function() {
        axios.post('/project', {
            dataArray: JSON.stringify(imgs),
            projectID: id
        })
            .then(function (response) {
                let returnData = response;
                console.log(returnData);
            })
            .catch(function (error) {
                console.log(error);
            });
    });
}

function populateCanvas(array) {
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