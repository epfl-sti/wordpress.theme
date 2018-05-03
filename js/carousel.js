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

   $('.carousel').on('slid.bs.carousel', function() {
       // Finish the animation ASAP — An animating div.carousel-item
       // has a "transition" CSS property, which blocks z-index bliss
       // ("creates a stacking context", in standardese) and therefore
       // causes the legend to render below the red wave, which is
       // unfortunate.
       //
       // (For some reason, Bootstrap carousel would wait for an
       // additional TRANSITION_DURATION = 600 ms here)
       $('.carousel-item').removeClass('carousel-item-right carousel-item-left carousel-item-next carousel-item-prev')
   });
});
