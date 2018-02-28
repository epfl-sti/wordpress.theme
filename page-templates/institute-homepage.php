<?php
/**
 * Template Name: Institute homepage
 * Template Post Type: page
 *
 * This template can be used to override the default template and sidebar setup
 *
 * @package epflsti
 */

get_header();
$container = get_theme_mod( 'epflsti_container_type' );
require_once(dirname(__FILE__).'/../inc/epfl.php');
use function \EPFL\STI\get_institute;
?>

<div class="wrapper" id="page-wrapper">

	<div class="<?php echo esc_attr( $container ); ?>" id="content">

		<div class="container">

			<div class="row">
		<?php dynamic_sidebar( get_institute()->get_code() . '-homepage' ); ?>

				<?php dynamic_sidebar( 'right' ); ?>

			</div><!-- .row -->

		</div><!-- .container -->


	</div><!-- Container end -->

</div><!-- Wrapper end -->

<?php 
$phonenumber="021 693 2222";
$email="ali.sayed@epfl.ch";
$office="B4102";
$sciper="283344";
$firstname="Ali";
$lastname="Sayed";
$epflname=str_replace('@epfl.ch', '', $email);
$title="Dean";
$desc="Prof. Sayed is world-renowned for his pioneering research on adaptive filters and adaptive networks, and in particular for the energy conservation and diffusion learning approaches he developed for the analysis and design of adaptive structures. His research interests span several areas including adaptation and learning, network and data sciences, information-processing theories, statistical signal processing, and biologically-inspired designs.";
?>



<div class=container>
 <div class="row entry-body">


<?php // The WHILE loop  ?>


  <div class="col-md-4 sti_decanat_box">

  <div class="sti_decanat_portrait"><img src="https://stisrv13.epfl.ch/img/decanat/portrait/<?php echo $epflname; ?>.jpg"></div>
  <div class="sti_decanat_grey">

   <table><td width=70%><div class=sti_decanat_name><?php echo "$firstname $lastname<br><strong>$title</strong"; ?></div></td>
    <td align=right>

   <div class="sti_decanat_buttons"> <!-- buttons -->
    <table>
     <td><img src=/wp-content/themes/epfl-sti/img/src/left_decanat.png></td>
     <td width=29><a title="phone: <?php echo $phonenumber; ?> " href="tel:<?php echo $phonenumber; ?>"><img onmouseover="this.src='/wp-content/themes/epfl-sti/img/src/phone_on.png';" onmouseout="this.src='/wp-content/themes/epfl-sti/img/src/phone_off.png';" src=/wp-content/themes/epfl-sti/img/src/phone_off.png></a></td>
     <td><a title="email: <?php echo $email; ?>" href="mailto:<?php echo $email; ?>"><img onmouseover="this.src='/wp-content/themes/epfl-sti/img/src/mail_on.png';" onmouseout="this.src='/wp-content/themes/epfl-sti/img/src/mail_off.png';" src=/wp-content/themes/epfl-sti/img/src/mail_off.png></a></td>
     <td><a title="office: <?php echo $office; ?>" href="http://plan.epfl.ch/?room=<?php echo $office; ?>"><img onmouseover="this.src='/wp-content/themes/epfl-sti/img/src/office_on.png';" onmouseout="this.src='/wp-content/themes/epfl-sti/img/src/office_off.png';" src=/wp-content/themes/epfl-sti/img/src/office_off.png></a></td>
     <td><a title='more about <?php echo "$firstname $lastname;" ?>' href="/epfl-person/<?php echo $sciper;?>"><img onmouseover="this.src='/wp-content/themes/epfl-sti/img/src/people_on.png';" onmouseout="this.src='/wp-content/themes/epfl-sti/img/src/people_off.png';" src=/wp-content/themes/epfl-sti/img/src/people_off.png></a></td>
    </table>
   </div> <!-- buttons -->
  </td></table>

  </div>
  <div class="sti_decanat_bar sti_textured_header_top"></div>

  <div class="sti_decanat_desc"><?php echo $desc?></div> 

  </div> <!-- col -->

<?php // end of WHILE ?>
 </div> <!-- row -->
</div> <!-- container -->




<?php get_footer(); ?>
<!-- template: institute-homepage.php -->
