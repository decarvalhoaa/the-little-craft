<?php 
/**
 * If this file is opened directly (e.g. for preview purpose), we load WP.
 * So you can use WP functions etc.
 */
if ( !defined('ABSPATH') ) include_once('../../../../wp-load.php'); 

/**
 * Then, you can use 2 available objects: $newsletter, $recipient
 * Uncomment 2 line below to view available properties.
 * E.g. the newsletter (post) ID is: $newsletter->ID
 */ 
//echo "<br />\n<pre>Newsletter=".print_r( $newsletter,true )."</pre>";
//echo "<br />\n<pre>Recipient=".print_r( $recipient,true )."</pre>";

/* Default newsletter string (English) */
$read_now           = 'Read Now';
$more_title         = 'More from our Blog';
$more_button        = 'Read more from our Blog';
$footer_copyright   = 'This email was sent with <font style="color:#ee6062;">&hearts;</font> by &copy; <a href="[SITE-URL]" target="new" class="links" style="margin:0;clear:both;text-align:center;line-height:24px;font-size:13px;color:#888;text-decoration:none;" >[SITE-NAME]</a>';
$footer_sender_id   = 'Owner: Antonio de Carvalho<br>An Der Fest 10, 40882 Ratingen, Germany<br>Tel.: +49 2102 5356836 | Email: info@thelittlecraft.com<br>';
$footer_unsubscribe = 'If you no longer want to receive messages from us, you can <u><a href="[USER-UNSUBSCRIBE-URL]" style="color:#aaa;font-size:12px;text-decoration:none;">unsubscribe here.</a></u>';

/* Footer page links */
$blog_page_id    = get_option( 'page_for_posts' );;
$privacy_page_id = 79;
$imprint_page_id = 80;
$terms_page_id   = 81;

if ( class_exists( 'ALO_EasyMail_Custom_Functions' ) && class_exists( 'Polylang' ) ) :
    $template = new ALO_EasyMail_Custom_Functions();
    
    if ( !is_object( $recipient ) ) $recipient = new stdClass();
	if ( empty( $recipient->lang ) ) $recipient->lang = alo_em_short_langcode ( get_locale() );
    
    /* Translate template strings */
    foreach ( $template->newsletter_strings as $name => $string ) {
        ${$name} = pll_translate_string( $string, $recipient->lang );
    }
    
    /* Translate footer page links */
    $blog_page_id    = pll_get_post( $blog_page_id, $recipient->lang );
    $privacy_page_id = pll_get_post( $privacy_page_id, $recipient->lang );
    $imprint_page_id = pll_get_post( $imprint_page_id, $recipient->lang );
    $terms_page_id   = pll_get_post( $terms_page_id, $recipient->lang );    
endif;

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <title>[SITE-NAME]</title>
        
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width">
        <meta content="text/html; charset=utf-8" http-equiv="Content-Type">	
        
        <style type="text/css">
            /* Responsive design */    
            @media screen and (max-width: 520px) {
                /* Full width table */
                table[class="full-width-table"] { width:100%!important; }
                /* Fluid images  */
                table[class="full-width-table"] img { width: 100% !important; height: auto !important; }
            }
            
            @media only screen and (max-device-width: 480px) {
                /* resize to mobile width */
                table[class="full-width-table"] { width: 100% !important; min-width:0px !important; }
                /* Fluid images  */
                table[class="full-width-table"] img { width: 100% !important; height: auto !important; }
            }
        </style>
    </head>
    <body style="margin: 0; padding: 0;">
        <!-- Container -->
        <table bgcolor="#ffffff" border="0" cellpadding="0" cellspacing="0" width="100%">
            <tbody>
                <!-- Site name -->
                <tr>
                    <td bgcolor="#ffffff" align="center" style="padding: 30px 15px 0px 15px;">
                        <table class="full-width-table" border="0" cellpadding="0" cellspacing="0" width="520">
                            <tbody>
                                <tr>
                                    <td class="site-name" bgcolor="#ffffff" align="left">
                                        <a href="[SITE-URL]" target="_blank" style="font-family:'Helvetica Neue', Helvetica, sans-serif; font-size: 42px; text-decoration:none; color: #ee6062;">
                                            <img src="shopify/thelittlecraft.jpg" alt="[SITE-NAME]" width="240" border="0" />
                                        </a>
                                    </td>
                                </tr>
                                <tr>
                                    <td>&nbsp;</td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
                <!-- End: Site name -->
                <!-- Main content -->
                <tr>
                    <td bgcolor="#ffffff" align="center" style="padding: 15px 15px 15px 15px;">
                        <table class="full-width-table" border="0" cellpadding="0" cellspacing="0" width="520">
                            <tbody>
                                <!-- Section -->
                                <tr>
                                    <td class="section" align="left" style="
                                      color: #919294;
                                      font-family: 'Helvetica Neue', Helvetica, sans-serif;
                                      font-size: 12px;
                                      font-weight: 500;
                                      line-height: 18px;
                                      letter-spacing: 0.1em;
                                      text-transform: uppercase;
                                      padding-top: 5px;
                                      padding-bottom: 5px;
                                      border-top: 1px solid #ededed;
                                      border-bottom: 1px solid #ededed;
                                    ">
                                        <font face="'Helvetica Neue', Helvetica, Arial, sans-serif">Newsletter</font>
                                    </td>
                                </tr>
                                <tr>
                                    <td>&nbsp;</td>
                                </tr>
                                <!-- End: Section -->
                                <!-- Title -->
                                <tr>
                                    <td class="title" align="left" style="
                                        color: #5f6062;
                                        font-family: 'Source Sans Pro', 'Helvetica Neue', Helvetica, Arial, sans-serif;
                                        font-size: 26px;
                                        font-weight: 700;
                                        line-height: 30px;
                                        padding-top: 5px;
                                        padding-bottom: 5px;
                                    ">
                                        <font face="'Helvetica Neue', Helvetica, Arial, sans-serif">[POST-TITLE-TXT]</font>
                                </tr>
                                <!-- End: Title -->
                                <!-- Content -->
                                <tr>
                                    <td class="content" align="left" style="
                                        color: #5f6062;
                                        font-family: 'Helvetica Neue', Helvetica, sans-serif;
                                        font-size: 16px;
                                        font-weight: 300;
                                        line-height: 24px;
                                        padding-top: 5px;
                                        padding-bottom: 5px;
                                        ">
                                            [CONTENT]
                                    </td>                                    
                                </tr>
                                <tr>
                                    <td>&nbsp;</td>
                                </tr>
                                <!-- End: Content -->
                                <!-- Call for action -->
                                <tr>
                                    <td align="left" style="padding-bottom: 30px;">
                                        <table border="0" cellpadding="0" cellspacing="0">
                                            <tbody>
                                                <tr>
                                                    <td bgcolor="#ee6260" align="center" style="
                                                        padding: 10px 20px 10px 20px;
                                                        -webkit-border-radius:3px;
                                                        border-radius:3px;
                                                        font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
                                                        font-size: 18px;
                                                        font-weight: 700;
                                                        ">
                                                        <font face="'Helvetica Neue', Helvetica, Arial, sans-serif;">
                                                            <a href="[POST-URL]" target="_blank" style="display: inline-block; color: #ffffff;  text-decoration: none; line-height: 24px; ">
                                                                <span style="color: #ffffff;"><?php echo $read_now ?></span>
                                                            </a>
                                                        </font>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </td>
                                </tr>
                                <!-- End: Call for action -->
                                <!-- Section -->
                                <tr>
                                    <td class="section" align="left" style="
                                      color: #919294;
                                      font-family: 'Helvetica Neue', Helvetica, sans-serif;
                                      font-size: 12px;
                                      font-weight: 500;
                                      line-height: 18px;
                                      letter-spacing: 0.1em;
                                      text-transform: uppercase;
                                      padding-top: 5px;
                                      padding-bottom: 5px;
                                      border-bottom: 1px solid #ededed;
                                    ">
                                        <font face="'Helvetica Neue', Helvetica, Arial, sans-serif"><?php echo $more_title ?></font>
                                    </td>
                                </tr>
                                <tr>
                                    <td>&nbsp;</td>
                                </tr>
                                <!-- End: Section -->
                                <!-- Latest Posts -->
                                <tr>
                                    <td class="latest" align="left" style="
                                    font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
                                    ">
                                        [LATEST-POSTS]
                                    </td>
                                </tr>
                                <!-- End: Latest Posts -->
                                <!-- Divider -->
                                <tr>
                                    <td style="border-bottom: 1px solid #ededed;">&nbsp;</td>
                                </tr>
                                <!-- End: Divider -->
                                <!-- Call to action -->
                                <tr>
                                    <td align="center" style="                        
                                        padding-top: 30px;
                                        padding-bottom: 15px;
                                        ">                            
                                        <table border="0" cellspacing="0" cellpadding="0">
                                            <tbody>
                                                <tr>
                                                    <td bgcolor="#ee6062" align="center" style="
                                                        padding: 10px 20px 10px 20px;
                                                        -webkit-border-radius:3px;
                                                        border-radius:3px
                                                        ">
                                                        <a href="<?php echo alo_em_make_url_trackable( $recipient, get_page_link( $blog_page_id ) ); ?>" target="_blank" style="
                                                            display: inline-block;
                                                            color: #ffffff;
                                                            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
                                                            font-size: 18px;
                                                            font-weight: 700;
                                                            text-decoration: none !important;
                                                            line-height: 24px;
                                                            ">
                                                                <font face="'Helvetica Neue', Helvetica, Arial, sans-serif;">
                                                                    <span style="color: #ffffff;"><?php echo $more_button ?></span>
                                                                </font>
                                                        </a>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </td>
                                </tr>
                                <!-- End: Call to action -->
                            </tbody>
                        </table>
                    </td>
                </tr>                
                <!-- End: Main content -->
                <!-- Footer -->
                <tr>
                    <td bgcolor="#fde7dd" align="center" style="color: #ededed; font-family: 'Helvetica Neue', Helvetica, sans-serif; font-size: 15px; font-weight: 400; line-height: 22px; padding-top: 30px; padding-bottom: 30px;">
                        <p style="color:#888;font-size:13px;padding-top:15px;margin-top:0;margin-bottom:0;margin-left:0;margin-right:0;">
                            <?php echo $footer_copyright ?>
                        </p>
                        <p style="padding-top:15px;margin-top:0;margin-bottom:0;margin-left:0;margin-right:0;clear:both;text-align:center;line-height:20px;font-size:12px;color:#aaa;text-decoration:none;">
                            <?php echo $footer_sender_id ?>
                            <a href="<?php echo alo_em_make_url_trackable( $recipient, get_page_link( $terms_page_id ) ); ?>" target="new" class="links" style="margin-top:0;margin-bottom:0;margin-right:0;margin-left:0;clear:both;text-align:center;line-height:20px;font-size:12px;color:#aaa;text-decoration:underline;" ><?php echo get_the_title( $terms_page_id ); ?></a>&nbsp;&nbsp;&nbsp;&nbsp;
                            <a href="<?php echo alo_em_make_url_trackable( $recipient, get_page_link( $privacy_page_id ) ); ?>" target="new" class="links" style="margin-top:0;margin-bottom:0;margin-right:0;margin-left:0;clear:both;text-align:center;line-height:20px;font-size:12px;color:#aaa;text-decoration:underline;" ><?php echo get_the_title( $privacy_page_id ); ?></a>&nbsp;&nbsp;&nbsp;&nbsp;
                            <a href="<?php echo alo_em_make_url_trackable( $recipient, get_page_link( $imprint_page_id ) ); ?>" target="new" class="links" style="margin-top:0;margin-bottom:0;margin-right:0;margin-left:0;clear:both;text-align:center;line-height:20px;font-size:12px;color:#aaa;text-decoration:underline;" ><?php echo get_the_title( $imprint_page_id ); ?></a>
                        </p>
                        <p align="center" style="padding-top:15px;margin-top:0;margin-bottom:0;margin-left:0;margin-right:0;clear:both;text-align:center;line-height:20px;font-size:12px;color:#aaa;text-decoration:none;" >
                            <?php echo $footer_unsubscribe ?>
                        </p>
                    </td>
                </tr>
                <!-- End: Footer -->
            </tbody>
        </table>
        <!-- End: Container -->
    </body>
</html>