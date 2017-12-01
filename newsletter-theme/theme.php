<?php

/*
Theme Name: EPFL STI
Description: Newsletter theme for the School of Engineering
*/

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
			 return "<tr> <td style='font-family:verdana,sans-serif; background-color: #c50813; color:white; padding: 4px 0px 4px 8px; '>$name</td> </tr>";
		       	} 
                        function format_date($day,$month) {
			       return "<table cellpadding=0 cellspacing=0 border=0 style='border:3px solid #aaa; background-color:#eee;'><td style='width: 36px; padding:0px 5px 0px 5px' align=center><span style='font-size:20px;'>$day</span><br><span style='font-weight: normal; color:#c50813; vertical-align:top;'><sup>$month</span></td></table>";
		        }
			function format_event($title,$day,$month,$link,$place,$outlink) {
			       $datebox=format_date($day,$month);
			       return "<a href=$link><table><tr><td style='font-size:14px; width:100%; padding: 0px 0px 10px 0px;' colspan=2>$title</td></tr><tr><td style='padding: 0px 0px 10px 0px;' width=50 align=left valign=top>$datebox</td><td style='font-size:12px;' align=right>$place<br>$outlink</td></tr><tr height=2><td colspan=2 height=2 style='background-color:#c50813; height:2px;'></td></tr></table></a>";
		        }
			function format_media($article,$link,$source,$date) {
			       return "<a href=#><table><tr><td style='font-size:14px; width:100%; padding: 0px 0px 10px 0px;'>$article</td></tr><tr><td style='font-size:10px;' align=right>$source, $date</td></tr><tr height=2><td colspan=2 height=2 style='background-color:#c50813; height:2px;'></td></tr></table></a>";
		        }
			$whitebg="style='background-color:white;'";
			$opentable="<table width=100% cellpadding=8 cellspacing=0 border=0>";
		       	$righthand_column=$opentable;
			$righthand_column.=redtitle("EVENTS");
			$righthand_column.="
			 <tr>
			  <td $whitebg>".format_event('Autonomous cars, intelligent cars','27','nov','#','EPFL Wallis','Add to calendar')." </td>
			 </tr>
			 <tr>
			  <td $whitebg>".format_event('Cutting diamonds to make optical components','28','nov','#','Forum Rolex','Add to calendar')."
			  </td>
			 </tr>
			 <tr>
			  <td $whitebg align=right><a href=#><table><td style='font-size:12px;'>More...</td></table></a> 
			  </td>
			 </tr>
			</table>
		       ";
		       $righthand_column.="<br><br>".$opentable;
		       $righthand_column.=redtitle("IN THE MEDIA");
		       $righthand_column.="
			 <tr>
			  <td $whitebg>".format_media('BBC feature Prof. Floreano&apos;s work on bio-inspired flight','#','BBC','14th November, 2017')."
			  </td>
			 </tr>
			 <tr>
			  <td $whitebg>".format_media('National Radio covers the two European projects to be led by Tobias Kippenberg','#','RTS','20th November, 2017')."
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

			 if ($count<5) { // for layout purposes, temporary

                         // Setup the post (WordPress requirement)
                         setup_postdata($post);

                         // The theme can "suggest" a subject replacing the one configured, for example. In this case
                         // the theme, is there is no subject, suggest the first post title.
                         if (empty($theme_options['subject'])) $theme_options['subject'] = $post->post_title;
			 if (($count<1)||($count>3)){
			  if ($count<1) {
				 $colspan="colspan=2"; #first article only
		   	  }
			  $rowstyle="' style='font-size: 18px; color: #000; text-decoration: none;font-family:Tahoma,Verdana,sans-serif'>";
			  $imagesize="width:250px";
  			 }
  			 else {
			  $colspan="colspan=1";	
			  $rowstyle="' style='font-size: 14px; color: #000; text-decoration: none;font-family:Tahoma,Verdana,sans-serif'>";
			  $imagesize="";
			 }
			 if ($count>3) {
			  echo "<tr><td><table cellpadding=0 cellspacing=0 border=0>";
			  echo redtitle("OPEN POSITIONS");
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
			 if ($count>3) {
			  echo "</table></td></tr>";
			 }
			 $count++; 

			 } // for layout purposes, temporary

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
			    <td colspan=2>
			  	<table cellpadding=0 cellspacing=0 border=0 width=100%>
			         <tr> <td style='height:10px; background-color: #c50813; width:100%'></td> </tr>
				 <tr>
				  <td style="background-color:white;font-size:10px; font-family:verdana,sans-serif;padding: 5px;" align=center>
                                   School of Engineering, &Eacute;cole Polytechnique F&eacute;d&eacute;rale de Lausanne (EPFL), Switzerland<br>
				You can access previous versions of the newsletter here
<br>
Unsubscribe by clicking <a target="_tab" href="{unsubscription_url}">here</a>
				 </td>
				 </tr>
			        </table>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </body>
</html>
