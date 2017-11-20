<?php

if (!defined('ABSPATH'))
    exit;

require_once(dirname(__FILE__) . '/inc/get_newsletter_posts.php');

$posts = get_newsletter_posts($theme_options);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
    <head>
        <title></title>
    </head>

    <body>
        <table bgcolor="#038" width="100%" cellpadding="1" cellspacing="0" border="0">
            <tr>
                <td align="center">
                    <table width="500" bgcolor="#ffffff" align="center" cellspacing="10" cellpadding="0" style="border: 1px solid #666;">
                        <tr>
                            <td>
                                <img src=/sti/wp-content/themes/epfl-sti/newsletter-theme/banner.gif>
                            </td>
                        </tr>
			<tr><td style="background-color:#fff; font-size: px; font-family: Tahoma, Verdana, sans-serif; border-left:1px solid #039; border-right:1px solid #039; border-top: 1px solid #039; border-bottom: 0px solid #eee; color: #029; padding:4px; border-top-right-radius:4px;border-top-left-radius: 4px;">News <div style='display:block; float:right'>24th November, 2017</div></td></tr>
                        <tr>
                            <td style="font-size: 14px; color: #666">
                            </td>
			</tr>
                        <?php
                        // Do not use &post, it leads to problems...
                        global $post;
                        foreach ($posts as $post) {

                            // Setup the post (WordPress requirement)
                            setup_postdata($post);

                            // The theme can "suggest" a subject replacing the one configured, for example. In this case
                            // the theme, is there is no subject, suggest the first post title.
                            if (empty($theme_options['subject']))
                                $theme_options['subject'] = $post->post_title;

                            // Extract a thumbnail, return null if no thumb can be found
                            $image = nt_post_image(get_the_ID());
                            ?>
                            <tr>
                                <td style="font-size: 14px; color: #666; font-family:Tahoma,Verdana,sans-serif">
                                    <?php if ($image != null) { ?>
                                        <img hspace=8 src="<?php echo $image; ?>" alt="picture" align="left"/>
                                    <?php } ?>
                                    <p><a target="_tab" href="<?php echo get_permalink(); ?>" style="font-size: 16px; color: #000; text-decoration: none;font-family:Tahoma,Verdana,sans-serif"><?php the_title(); ?></a></p>

                                    <?php the_excerpt(); ?>
                                </td>
                            </tr>
                            <?php
                        }
                        ?>
                        <?php if (!isset($theme_options['theme_social_disable'])) { ?> 
                            <tr>
                                <td  style="font-family: Tahoma,Verdana,sans-serif; font-size: 12px">
                                    <?php include WP_PLUGIN_DIR . '/newsletter/emails/themes/default/social.php'; ?>
                                </td>
                            </tr>
                        <?php } ?>
                        <tr>
                            <td style="border-top: 1px solid #eee; border-bottom: 1px solid #eee; font-size: 12px; color: #999; font-family:Tahoma, Verdana, sans-serif;">
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
