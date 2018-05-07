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

    // The next carousel item goes from display:none to display:block,
    // which causes the browser to skip the animation and render
    // directly in the final state (although that appears to depend on
    // the specifics of the animation, i.e. a "transform" is treated
    // differently - Go figure). MDN says to fix that by adding
    // another class a jiffy later.
    $('.carousel').on('slide.bs.carousel', function() {
        var self = this;
        setTimeout(function() {
            var $comingNext = $(".carousel-item-next, .carousel-item-prev", self);
            $comingNext.addClass("ready-set-go");
            $('.carousel').one('slid.bs.carousel', function() {
                $comingNext.removeClass("ready-set-go");
            });
        }, 1);  // "A handful of milliseconds" as per
                // https://developer.mozilla.org/en-US/docs/Web/CSS/CSS_Transitions/Using_CSS_transitions#JavaScript_examples
    });
});
