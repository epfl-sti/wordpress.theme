<?php
/**
 * The template for displaying the front page.
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site will use a
 * different template.
 *
 * @package epflsti
 */

get_header();

?>
<style>
div.news {
display: flex;
flex-direction: row;
flex-wrap: wrap;
justify-content: space-around;
}
</style>

<div>

    <div id="sti-homecarousel" class="carousel slide" data-ride="carousel">
        <div class="carousel-inner" role="listbox">

            <div class="carousel-item">
                <div class="sti_carousel">
                    <div class="div-wrapper" id="containerwave" style="background-image:url('<?php echo get_stylesheet_directory_uri(); ?>/img/ProfCamilleBres.jpg');">
                        <img src="<?php echo get_stylesheet_directory_uri(); ?>/img/waving.png">
                    </div>
                    <div class="pixelman">
                        <div id="containernews">
                            <a class="titlelink" href="http://sti.epfl.ch/page-108381.html#anchor2019">Early career award in photonics </a><br>
                            <a class="titlelink subtitlelink" href="http://sti.epfl.ch/page-108381.html#anchor2019">Prof. Camille Brès has received the Early Career Women/Entrepreneur award in Photonics</a><br>
                        </div>
                    </div>
                </div>
            </div>

            <div class="carousel-item">
                <div class="sti_carousel">
                    <div class="div-wrapper" id="containerwave" style="background-image:url('<?php echo get_stylesheet_directory_uri(); ?>/img/LacourTeam.jpg');">
                        <img src="<?php echo get_stylesheet_directory_uri(); ?>/img/waving.png">
                    </div>
                    <div class="pixelman">
                        <div id="containernews">
                            <a class="titlelink" href="#">A long-term implant to restore walking</a><br>
                            <a class="titlelink subtitlelink" href="#">Prof. Stéphanie Lacour of the Institute of Bioengineering</a><br>
                        </div>
                    </div>
                </div>
            </div><!-- .carousel-item -->
        </div>
        <a class="sti-carousel-button prev" href="#sti-homecarousel" role="button" data-slide="prev">
           <span class="carousel-control-prev-icon" aria-hidden="true"></span>
           <span class="sr-only">Previous</span>
        </a>
        <a class="sti-carousel-button next" href="#sti-homecarousel" role="button" data-slide="next">
           <span class="carousel-control-next-icon" aria-hidden="true"></span>
           <span class="sr-only">Next</span>
        </a>
    </div><!-- .sti-homecarousel -->
</div>

<style>
#containernews {
    height: 60px;
    position: relative;
    top: -120px;
    padding: 0px 0px 0px 45px;
    z-index: 3;
}

#containerwave {
    position: relative;
    top: 0px;
    z-index: 2;
    background-position: center top;
    width:100%;
    background-size: cover;
}

@media screen and (max-width: 780px) {
    #containerwave {
        height: 450px;
    }
}
@media screen and (min-width: 680px) {
    #containerwave {
        height: 600px;
    }
}

.div-wrapper {
    position: relative;
    height: 300px;
    width: 300px;
}

.div-wrapper img {
    position: absolute;
    left: 0;
    bottom: 0;
}
</style>
<script>
    jQuery(function() {
        window.frontpage_init();
    });
</script>


<div class="news">
<?php
    // Get the news from stisrv13
    $atts = array('tmpl' => 'bootstrap-card', 'number' => 14);
    echo epfl_actu_wp_shortcode($atts);

    $newsids = ["news", "researchvideo", "inthenews", "testimonials", "campus", "appointments", "whatis", "research", "placement", "masters"];
    foreach ($newsids as $newsid) {
        $newshtml = curl_get("https://stisrv13.epfl.ch/cgi-bin/whoop/thunderbird.pl?look=leonardo&lang=eng&id=" . $newsid);
        echo "<div class=\"sti_news_html\">$newshtml</div>";
    }

?>
</div>
<?php get_footer(); ?>
