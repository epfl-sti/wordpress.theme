<?php

namespace EPFL\STI\Theme\Carousel;

function render ()
{
?>
<div id="container-carousel" class="carousel slide" data-ride="carousel">
    <div class="carousel-item">
        <img src="<?php echo get_stylesheet_directory_uri(); ?>/img/ProfCamilleBres.jpg');">
        <div class="legend">
                <h1><a href="https://sti.epfl.ch/page-108381.html#anchor2019">Early career award in photonics </a></h1>
                <h2><a href="https://sti.epfl.ch/page-108381.html#anchor2019"></a></h2>
        </div>
    </div>

    <div class="carousel-item">
        <img src="<?php echo get_stylesheet_directory_uri(); ?>/img/LacourTeam.jpg');">
        <div class="legend">
                <h1><a href="XXX">A long-term implant to restore walking</a></h1>
                <h2><a href="XXX">Prof. Lacour's team</a></h2>
        </div>
    </div><!-- .carousel-item -->
    <a class="sti-carousel-button prev" href="#container-carousel" role="button" data-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="sr-only">Previous</span>
    </a>
    <a class="sti-carousel-button next" href="#container-carousel" role="button" data-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="sr-only">Next</span>
    </a>
</div>
<?php # The wave must be outside the carousel, so as not to be clipped. ?>
<div id="redwave">
    <img src="<?php echo get_stylesheet_directory_uri(); ?>/img/waving.png">
</div>
<?php
}
render();
