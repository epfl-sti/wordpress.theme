<?php
/**
 * Search results partial template.
 *
 * @package epflsti
 */

?>
<!-- epflsti:loop-templates/content-search.php -->
<div class="fullwidth-list-item" id="post-<?php the_ID(); ?>">
  <h2>
    <a href="<?php esc_url( get_permalink() )  ?>" rel="bookmark"><?php the_title() ?></a>
    <span class="top-right"><?php echo get_the_date( 'jS F, Y' ); ?></span>
  </h2>
  <div class="container">
    <div class="row entry-body">
      <div class="col-md-2">
        <div class="text-center actu-details search-details">
          <a href="<?php echo esc_url( get_permalink() ); ?>"><?php echo get_the_post_thumbnail(get_the_ID(), 'entry_without_sidebar'); ?></a>
        </div>
      </div>
      <div class="col-md-10 actu-item-content">
        <div class="search-result-summary">
          <?php if ( 'post' == get_post_type() ) : ?>
            <div class="search-result-meta">
              <?php the_excerpt(); ?>
            </div>
          <?php else: ?>
            <div class="search-result-meta">
              Page: <a href="<?php echo esc_url( get_permalink() ); ?>"><?php the_title(); ?></a>
            </div><br />
          <?php endif; ?>
        </div>
        <div class="actu-authors text-right font-italic">
          <?php epflsti_entry_footer(); ?>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- end of epflsti:loop-templates/content-search.php -->