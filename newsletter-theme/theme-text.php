<?php

namespace EPFL\STI\Newsletter;

if (!defined('ABSPATH'))
    exit;

require_once(dirname(__FILE__) . '/inc/newsletter_state.php');

?><?php echo $theme_options['theme_opening_text']; ?>

* <?php echo $theme_options['theme_title']; ?>

<?php foreach (get_newsletter_posts($theme_options) as $cat): ?>

=== <?php echo $cat->title(); ?>

<?php
global $post;
foreach ($cat->posts() as $post):
    // Setup the post (WordPress requirement)
    setup_postdata($post);
?>
<?php the_title(); ?>

<?php the_permalink(); ?>


<?php endforeach;  # posts ?>
<?php endforeach;  # categories ?>


<?php echo $theme_options['theme_footer_text']; ?>

