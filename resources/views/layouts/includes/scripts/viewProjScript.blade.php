<script>
    $(document).ready(function() {
        updatePageCount();
        $('#next').on('click', function() {
            goNext();
        });
        $('#prev').on('click', function() {
            goPrev();
        });
        $('.entryNav').on('click', function() {
            let activeElm = $('div.entry.active').data('id');
            let thisElm = $(this).data('id');
            if(activeElm === thisElm) {
                return false;
            }
            alert(activeElm);
        });
    });
    function goNext() {
        let currEntry = $('div.entry.active');
        let maxElm = currEntry.data('numelm');
        let currImg = $('div.image.active', currEntry);

        let currImgNum = currImg.data('num');
        let nextImg = currImgNum + 1;
        if(parseInt(nextImg) >= parseInt(maxElm)) {
            nextImg = 0;
        }

        currImg.fadeOut(200, function() {
            $(this).removeClass('active');
            $('div.image.proj_' + nextImg, currEntry).fadeIn(200, function() {
                $(this).addClass('active');
                updatePageCount();
            });
        });
    }

    function goPrev() {
        let currEntry = $('div.entry.active');
        let maxElm = parseInt(currEntry.data('numelm')) - 1;
        let currImg = $('div.image.active', currEntry);

        let currImgNum = currImg.data('num');
        let nextImg = currImgNum - 1;
        if(parseInt(nextImg) < 0) {
            nextImg = maxElm;
        }

        currImg.fadeOut(200, function() {
            $(this).removeClass('active');
            $('div.image.proj_' + nextImg, currEntry).fadeIn(200, function() {
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
</script>