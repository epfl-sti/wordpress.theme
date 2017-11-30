<?php

namespace EPFL\STI\Newsletter;

if (!defined('ABSPATH'))
    exit;

require_once(dirname(dirname(__FILE__)) . "/inc/epfl.php");
use function \EPFL\STI\get_theme_relative_uri;

require_once(dirname(__FILE__) . '/inc/newsletter.php');

function css()
{ ?>
<style type="text/css">
.newsletter-title {
 background-color:#039;
 font-size: 20px;
 font-family: Verdana, sans-serif;
 color: #feea45;
 padding:4px;
}
<?php # https://stackoverflow.com/a/18721938/435004 ?>
td > img {
    vertical-align: top;
}
p {
 padding: 0px;
 margin: 0px;
}
</style>
<?php }

// <table>s everywhere is the way to go - Not sure how ancient versions of Outlook
// like HTML5 stuff.

$date = "Vol. 1, No. 1, November 2017";

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
    <head>
        <title></title>
        <?php css(); ?>
    </head>

    <body>
        <table bgcolor="white" width="100%" cellpadding="4" cellspacing="0" border="0">
            <tr>
                <td align="center">
                    <table width="737" bgcolor="#d6d6d6" align="center" cellspacing="14" cellpadding="0">
                        <tr>
                            <td colspan=2>
				<table cellpadding=0 cellspacing=0 border=0>
				 <tr>
                                  <td><img src="<?php echo get_theme_relative_uri() . "/newsletter-theme/banner.gif"; ?>"/></td>
				 </tr>
				 <tr>
                                  <td align=right style="padding: 0px 8px 0px 0px; margin: 0px; font: bold 9px verdana; background-color:white;"><?php echo $date; ?></td>
				 </tr>
				 <tr>
                                  <td><img src="<?php echo get_theme_relative_uri() . "/newsletter-theme/outrider.gif"; ?>" style="display: block;"/></td>
				 </tr>
				</table> 

                            </td>
                        </tr>
			<tr>
				<td colspan=2>
					<img src="<?php echo get_theme_relative_uri() . "/newsletter-theme/feature.png"; ?>"/></td>
			</tr>
                       <?php 
		       	function redtitle($name) {
			 return "<tr> <td style='background-color: #c50813; color:white; padding: 4px 0px 4px 8px; '>$name</td> </tr>";
		       	} 
			$whitebg="style='background-color:white;'";
			$opentable="<table width=100% cellpadding=0 cellspacing=0 border=0>";
		       	$righthand_column=$opentable;
			$righthand_column.=redtitle("EVENTS");
			$righthand_column.="
			 <tr>
			  <td $whitebg>phew
			  </td>
			 </tr>
			 <tr>
			  <td $whitebg>event
			  </td>
			 </tr>
			 <tr>
			  <td $whitebg>more
			  </td>
			 </tr>
			</table>
		       ";
		       $righthand_column.="<br><br>".$opentable;
		       $righthand_column.=redtitle("IN THE MEDIA");
		       $righthand_column.="
			 <tr>
			  <td $whitebg>phew
			  </td>
			 </tr>
			 <tr>
			  <td $whitebg>event
			  </td>
			 </tr>
			 <tr>
			  <td $whitebg>more
			  </td>
			 </tr>
			</table>
		       ";

		       $index = 0;
		       $count = 0;
                       foreach (get_newsletter_categories($theme_options) as $cat):
                                  $index = $index + 1;
                        // Do not use &post, it leads to problems...
                        global $post;
                        foreach ($cat->posts() as $post):

                         // Setup the post (WordPress requirement)
                         setup_postdata($post);

                         // The theme can "suggest" a subject replacing the one configured, for example. In this case
                         // the theme, is there is no subject, suggest the first post title.
                         if (empty($theme_options['subject'])) $theme_options['subject'] = $post->post_title;
			 if ($count<1) {
			  $colspan="colspan=2"; #first article only
			  $rowstyle="' style='font-size: 18px; color: #000; text-decoration: none;font-family:Tahoma,Verdana,sans-serif'>";
			  $imagesize="width:250px";
  			 }
  			 else {
			  $colspan="colspan=1";	
			  $rowstyle="' style='font-size: 14px; color: #000; text-decoration: none;font-family:Tahoma,Verdana,sans-serif'>";
			  $imagesize="";
			 }
                         echo "<tr> <td width=450 $colspan style='padding: 20px 10px 20px 10px; background-color:#fff; font-size: 13px; color: #666; font-family:Tahoma,Verdana,sans-serif'>";
                         $image_path = get_thumb_relpath(wp_get_attachment_metadata(get_post_thumbnail_id(get_the_id())));
                          if ($image_path) {
                         echo "<img hspace=8 src='$image_path' style='$imagesize' alt='picture' align='left'/>";
                         }
                         echo "<p><a target='_tab' href='";
			 echo get_permalink();  
			 echo $rowstyle; 
			 echo the_title(); 
			 echo"</a></p>";
                         the_excerpt(); 
                         echo "</td>";
                         if ($count === 1) { 
			  echo "<td rowspan=4 valign=top style='padding: 0px; background-color:#d6d6d6; font-size: 14px; color: #666; font-family:Tahoma,Verdana,sans-serif'>$righthand_column</td>";
			 }
		 	 echo " </tr>";
			 $count++; 
                        endforeach; # posts 
                       endforeach; # categories 
                         if (!isset($theme_options['theme_social_disable'])) { ?> 
                            <tr>
                                <td  style="font-family: Tahoma,Verdana,sans-serif; font-size: 12px">
                                    <?php include WP_PLUGIN_DIR . '/newsletter/emails/themes/default/social.php'; ?>
                                </td>
                            </tr>
                        <?php } ?>
                        <tr>
                            <td colspan=2 style="border-top: 1px solid #eee; border-bottom: 1px solid #eee; font-size: 12px; color: #999; font-family:Tahoma, Verdana, sans-serif;">
                                <p>Dear {name}, this is the <?php echo get_option('blogname'); ?> newsletter.</p>
                                You received this email because you subscribed for it as {email}. You can unsubscribe by clicking <a target="_tab" href="{unsubscription_url}">here</a>.
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </body>
</html>
