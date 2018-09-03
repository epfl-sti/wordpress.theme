<?php

/**
 * Loop template invoked for posts that have "format" set to "video".
 *
 * For instance,
 * https://sti-test.epfl.ch/fr/category/temoignages-detudiants/ shows these.
 */
echo "<!-- epflsti:loop-templates/content-video.php -->";
the_title( '<h1>', '</h1>' );
the_content();

?>
