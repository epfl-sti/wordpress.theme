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

function render_css()
{ ?>
<style>
.newsletter-title {
 background-color:#039;
 font-size: 20px;
 font-family: Verdana, sans-serif;
 color: #feea45;
 padding:4px;
}
<?php # https://stackoverflow.com/a/18721938/435004 ?>
td > img, td > a > img {
    vertical-align: top;
}
p {
 padding: 0px;
 margin: 0px;
}

a {
 text-decoration: none;
 color: black;
}

a.outlink.more {
    font-size:12px;
}

table.righthand-column td {
    background-color:white;
}

.redtitle {
    text-align: inherit;
    font-family: verdana,sans-serif;
    font-weight: inherit;
    color:white;
    background-color: #c50813;
    padding: 4px 0px 4px 8px;
}

.date {
    border:3px solid #aaa;
    background-color:#eee;
    width: 36px;
    padding:0px 5px 0px 5px;
    text-align: center;
}

.date .day {
    font-size:20px;
}

.date .month {
    font-weight: bold;
    color:#c50813;
    vertical-align:top;
}

.newstitle {
    font-size: 14px;
    font-family:Tahoma,Verdana,sans-serif;
}

.newstitle.hero {
    font-size: 18px;
}

#footer td {
    border-top:10px solid #c50813;
    background-color:white;
    padding: 5px;
    text-align: center;
    font-size:10px;
    font-family:verdana,sans-serif
}

#footer a {
    color: #c50813;
    text-decoration: none;
    font-weight: bold;
}

</style>
<?php }


// <table>s everywhere is the way to go - Ancient versions of Outlook
// probably don't like HTML5 stuff much.

function render_frame_table ($render_inside_func) {
    ?>
    <table bgcolor="white" width="100%" cellpadding="4" cellspacing="0" border="0">
    <tr>
        <td align="center">
            <table width="737" bgcolor="#d6d6d6" align="center" cellspacing="14" cellpadding="0">
               <?php call_user_func($render_inside_func); ?>
             </table>
        </td>
    </tr>
    </table>
    <?php
}

function render_header_tr ($volumeno)
{
    ?>
            <tr>
                <td colspan=2>
				<table cellpadding=0 cellspacing=0 border=0>
				 <tr>
                                  <td><a href=http://sti.epfl.ch><img src="<?php echo get_theme_relative_uri() . "/newsletter-theme/banner.gif"; ?>"/></a></td>
				 </tr>
				 <tr>
                                  <td align=right style="padding: 0px 8px 0px 0px; margin: 0px; font: bold 9px verdana; background-color:white;"><?php echo $volumeno; ?></td>
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
}

function render_red_title_tr ($name)
{
    echo "<tr><th class=\"redtitle\">$name</th> </tr>";
}

function render_media_tr ($article, $link, $source, $date)
{
    echo "<tr><td><table><tr><td style='font-size:14px; width:100%; padding: 0px 0px 10px 0px;'><a href=\"$link\">$article</a></td></tr><tr><td style='font-size:10px;' align=right>$source, $date</td></tr><tr><td style='border-bottom:2px solid #c50813; font-size:1px'>&nbsp;</tr></table></td></tr>";
}

function render_event_tr ($title, $day, $month, $link, $place, $outlink)
{
    $datebox = "<div class=\"date\"><a href=\"$link\"><span class=\"day\">$day</span><br><span class=\"month\">$month</span></a></div>";
    ?>
    <tr><td><table><tr><td style="font-size:14px; width:100%; padding: 0px 0px 10px 0px;" colspan=2><a href="<?php echo $link; ?>"><?php echo $title; ?></a></td></tr><tr><td style='padding: 0px 0px 10px 0px;' width=50 align=left valign=top><?php echo $datebox; ?></td><td style="font-size:12px;" align=right><?php echo $place; ?><br><a href="<?php echo $outlink; ?>">Add to calendar</a></td></tr><tr><td colspan=2 style='border-bottom:2px solid #c50813; font-size:1px'>&nbsp;</td></tr></table></td></tr>
    <?php
}

function render_righthand_column_td ($render_events_func, $render_in_the_media_func)
{
    echo "<td rowspan=4 valign=top style=\"padding: 0px; background-color:#d6d6d6; font-size: 14px; color: #666; font-family:Tahoma,Verdana,sans-serif\">\n";

    $opentable = "<table class=\"righthand-column\" width=\"100%\" cellpadding=\"8\" cellspacing=\"0\" border=\"0\">";
			echo "$opentable";
			render_red_title_tr("EVENTS");
            call_user_func($render_events_func);
                echo "
			 <tr>
			  <td align=right><table><td><a href=\"https://sti.epfl.ch/seminars\" class=\"outlink more\">More...</a></td></table></td>
			 </tr>
			</table>
		       ";
            if ($render_in_the_media_func) {
		       echo "<br>$opentable";
		       render_red_title_tr("IN THE MEDIA");
               call_user_func($render_in_the_media_func);
              echo "
			 <tr>
			  <td align=right><table><td><a href=\"https://sti.epfl.ch/news\" class=\"outlink more\">More...</a></td></table></td>
			 </tr>
			</table>
";
           }
           echo "
        </td>
";
}

function render_news_item_td ($style)
{
    if ($style === "hero") {
        $colspan="colspan=2"; #first article only
    } else {
        $colspan="colspan=1";	
    }
    if ($style === "hero" or $style === "large") {
        $link_class="newstitle $style";
        $imagesize="width:250px";
    } else {
        $link_class="newstitle";
        $imagesize="";
  }

    echo "<td width=450 $colspan style='padding: 20px 10px 20px 10px; background-color:#fff; font-size: 13px; color: #666; font-family:Tahoma,Verdana,sans-serif'>";

    $img = get_the_post_thumbnail(get_the_id(), 'post-thumbnail', array(
        "style"  => $imagesize,
        "hspace" => "18",
        "alt"    => "picture",
        "align"  => "left"
    ));
    if ($img) echo $img;
    echo sprintf("<p><a target='_blank' href=\"%s\" class=\"%s\">%s</a></p>",
                 get_permalink(),
                 $link_class,
                 the_title("", "", false));
    the_excerpt(); 
    echo "</td>";
}

function render_footer_tr ()
{
    ?>
            <tr>
			    <td colspan=2>
			    <table id='footer' cellpadding=0 cellspacing=0 border=0 width=100%>
				 <tr>
				  <td>
                         <a href="https://sti.epfl.ch">School of Engineering</a>, &Eacute;cole Polytechnique F&eacute;d&eacute;rale de Lausanne (<a href="https://www.epfl.ch">EPFL</a>), Switzerland<br>You can access previous versions of the newsletter <a href="#">here</a>
<br>
Unsubscribe by clicking <a target="_blank" href="{unsubscription_url}">here</a>
				 </td>
				 </tr>
			    </table>
                </td>
            </tr>
    <?php
}

function render_events ()
{
    render_event_tr(
        'Towards next-generation membranes for energy-efficient molecular separation',
        '7','dec',
        'http://sti.epfl.ch/page-42995.html#httpsmementoepflchimage10622122x122jpg',
        'EPFL Wallis (Sion)',
        'https://stisrv13.epfl.ch/outlink.php?enddate=20171207T113000&datestring=20171207T103000&speaker=Prof.%20Kumar%20AgrawalLaboratory%20of%20advanced%20separationsEPFL%20Valais,%20Sion&title=Towards%20the%20next-generation%20membranes%20for%20energy-efficient%20molecular%20separation&room=Zeuzier,%20I17%204%20K2');
    render_event_tr(
        'What impact can integrated photonics have on data center architecture?',
        '8','dec',
        'http://sti.epfl.ch/page-42995.html#httpsmementoepflchimage10667122x122jpg',
        'BC 420',
        'https://stisrv13.epfl.ch/outlink.php?enddate=20171208T120000&datestring=20171208T110000&speaker=S%E9bastien%20Rumley,%20Research%20Scientist%20in%20the%20Lightwave%20Research%20Laboratory,%20Columbia%20University,%20New%20York&title=What%20impact%20can%20Integrated%20Photonics%20have%20on%20data%20center%20architecture?&room=BC%20420');
}

function render_in_the_media ()
{
    render_media_tr('Sylvie Roke makes waves with her work on 3D imaging of surface chemistry','https://www.eurekalert.org/pub_releases/2017-07/epfd-3io071917.php','Science News','September 2017');
    render_media_tr('Elison Matioli makes groundbreaking use of slanted tri-gate structures','http://www.semiconductor-today.com/news_items/2017/aug/epf_230817.shtml','Semiconductor Today','August, 2017');
}

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
    <head>
        <title></title>
        <?php render_css(); ?>
        <?php if (function_exists("\\EPFL\\STI\\Newsletter\\render_editor_scripts")) {
                 render_editor_scripts();
        } ?>
    </head>

    <body>
<?php
render_frame_table(function() {
    $volumeno = "Vol. 1, No. 1, December 2017";
    render_header_tr($volumeno);
    $index = 0;
    $count = 0;
    foreach (get_newsletter_categories($theme_options) as $cat_id => $cat) {
        $index = $index + 1;
        global $post;
        foreach ($cat->posts() as $post) {
            if ($count<5) { // for layout purposes, temporary
    
                // Setup the post (WordPress requirement)
                setup_postdata($post);
    
                if ($count>3) {
                    echo "<tr><td><table cellpadding=0 cellspacing=0 border=0>";
                    render_red_title_tr("OPEN POSITIONS");
                }
                echo "<tr>";
                render_news_item_td($count === 0 ? "hero" : "normal");
                if ($count === 1) {
                    render_righthand_column_td(
                        "EPFL\\STI\\Newsletter\\render_events",
                        "EPFL\\STI\\Newsletter\\render_in_the_media");
                }
                echo " </tr>";
                if ($count>3) {
                    echo "</table></td></tr>";
                }
            } // for layout purposes, temporary
            $count++;
        } # posts
    } # categories
    render_footer_tr();
});  // end of function passed to render_frame_table
    ?>
    </body>
</html>
