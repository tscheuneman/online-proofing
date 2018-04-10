<script>
    $(document).ready(function() {
        updatePageCount();
        $('#next').on('click', function() {
            goNextItemProjectView();
        });
        $('#prev').on('click', function() {
            goPreviousItemProjectView();
        });
        $('.entryNav').on('click', function() {
            let activeElm = $('div.entry.active').data('id');
            let thisElm = $(this).data('id');
            if(activeElm === thisElm) {
                return false;
            }
            $('.entryNav').removeClass('active');
            $(this).addClass('active');
            goToEntryItemById(thisElm);
        });
    });
</script>