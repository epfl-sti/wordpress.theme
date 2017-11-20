<?php

if (!defined('ABSPATH'))
    exit;

require_once(dirname(__FILE__) . '/inc/get_newsletter_posts.php');

$posts = get_newsletter_posts($theme_options);

?><?php echo $theme_options['theme_opening_text']; ?>

* <?php echo $theme_options['theme_title']; ?>


<?php
global $post;
foreach ($posts as $post) {
    // Setup the post (WordPress requirement)
    setup_postdata($post);
?>
<?php the_title(); ?>

<?php the_permalink(); ?>


<?php } ?>


<?php echo $theme_options['theme_footer_text']; ?>

