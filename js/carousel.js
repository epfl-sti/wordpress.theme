jQuery(function ($) {
    $( ".carousel-item" ).first().addClass( "active" );

    // https://codepen.io/andrearufo/pen/rVWpyE
    $(".carousel.slide").swipe({
        swipeLeft: function() {
            console.log("Swipe left");
            $(this).carousel('next');
        },
        swipeRight: function() {
            console.log("Swipe right");
            $(this).carousel('prev');
        },
        allowPageScroll:"vertical"
    });
});
