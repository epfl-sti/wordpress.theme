jQuery(function carousel_init($) {
    $( ".carousel-item" ).first().addClass( "active" );
    // https://codepen.io/andrearufo/pen/rVWpyE
    $("#sti-homecarousel").swipe({
        swipe: function(event, direction, distance, duration, fingerCount, fingerData) {

            if (direction == 'left') $(this).carousel('next');
            if (direction == 'right') $(this).carousel('prev');

        },
        allowPageScroll:"vertical"
    });
});
