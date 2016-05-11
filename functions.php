<?php

/**
 * Loads the StoreFront parent theme stylesheet.
 */

function sf_child_theme_enqueue_styles() {

    wp_enqueue_style( 'storefront-child-style', get_stylesheet_directory_uri() . '/style.css', array( 'storefront-style' ) );

}
add_action( 'wp_enqueue_scripts', 'sf_child_theme_enqueue_styles' );

/**
 * Note: DO NOT! alter or remove the code above this text and only add your custom PHP functions below this text.
 */


/**
 * Load custom javascript
 */
function tlc_load_javascript_files() {
    wp_enqueue_script( 'tlc_custom_js', get_stylesheet_directory_uri() . '/js/tlc_custom_js.js', array('jquery'), '1.0.0', true );
}
add_action( 'wp_enqueue_scripts', 'tlc_load_javascript_files' ); // If using a cache plugin, set priority to 9 to load before WooCommerce scripts, otherwise breaks my js refresh


/**
 * Load PrettyPhoto for the whole site
 */
function tlc_frontend_scripts_include_lightbox() {
    // Test if WooCommerce is activated
    if ( did_action( 'woocommerce_loaded' ) ) {
	global $woocommerce;

	$suffix      = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';
	$lightbox_en = get_option( 'woocommerce_enable_lightbox' ) == 'yes' ? true : false;

	if ( $lightbox_en ) {
	    wp_enqueue_script( 'prettyPhoto', $woocommerce->plugin_url() . '/assets/js/prettyPhoto/jquery.prettyPhoto' . $suffix . '.js', array( 'jquery' ), $woocommerce->version, true );
	    wp_enqueue_script( 'prettyPhoto-init', $woocommerce->plugin_url() . '/assets/js/prettyPhoto/jquery.prettyPhoto.init' . $suffix . '.js', array( 'jquery' ), $woocommerce->version, true );
	    wp_enqueue_style( 'woocommerce_prettyPhoto_css', $woocommerce->plugin_url() . '/assets/css/prettyPhoto.css' );
	}
    }
}
add_action( 'wp_enqueue_scripts', 'tlc_frontend_scripts_include_lightbox' );


/**
 * Configure auto_uploader
 */
// Disable all automatic updates
//add_filter( 'automatic_updater_disabled', '__return_true' );

// Disable all core-type updates
add_filter( 'auto_update_core', '__return_false' );

// Disable minor core releases updates
//add_filter( 'allow_minor_auto_core_updates', '__return_false' );

//Disable major core releases updates
//add_filter( 'allow_major_auto_core_updates', '__return_false' );

// Disable  (including security team updates)
add_filter( 'auto_update_plugin', '__return_false' );

// Disable ( including security team updates )
add_filter( 'auto_update_theme', '__return_false' );

// Disable translation file updates
add_filter( 'auto_update_translation', '__return_false' );

// Disable Emails updates
add_filter( 'auto_core_update_send_email', '__return_false' );


/**
 * Load custom text domain and overrides to woocommerce plugin and parent theme
 * translations
 */
add_action( 'after_setup_theme', function () {
    // load custom translation file for woocommerce plugin
    //load_theme_textdomain( 'woocommerce', get_stylesheet_directory() . '/languages/woocommerce' );
    // load custom translation file for parent theme
    //load_theme_textdomain( 'storefront', get_stylesheet_directory() . '/languages/storefront' );
    // load translation file for the child theme
    load_child_theme_textdomain( 'thelittlecraft', get_stylesheet_directory() . '/languages' );
} );


/**
 * Set the WooCommerce session cookie on-demand for specific pages.
 * Note: The session cookie is not created until the cart has contents. This is
 * to make it more friendly with varnish caching. However,in cases where it is
 * required to pass data to the next page, e.g. wc_add_notice() the data will be
 * lost if the session is not initiated.
 */
function tlc_init_wc_session_cookie() {
    // Test if WooCommerce is activated
    if ( did_action( 'woocommerce_loaded' ) ) {
	// List of page ids, titles or slugs that require WooCommerce session
	// creation (array of comma separated strings)
	$session_pages = array( 'my-account', 'mein-konto' );

	// Check the current page against the session_pages list and instantiate the
	// session if the page is in the list and the user is not already logged in.
	// If the session already exists from having created a cart, WooCommerce will
	// not destroy the active session
	if ( is_page( $session_pages ) && ! is_user_logged_in() ) {
	    global $woocommerce;

	    $woocommerce->session->set_customer_session_cookie( true );
	}
    }
}
add_action( 'wp', 'tlc_init_wc_session_cookie' );

/**
 * Remove WooCommerce review tab - removes the ability to write reviews and rate
 * products.
 */
function tlc_remove_woocommerce_reviews_tab( $tabs ) {
    unset($tabs['reviews']);
    return $tabs;
}
//add_filter( 'woocommerce_product_tabs', 'tlc_remove_woocommerce_reviews_tab', 98 );


/**
 * Custom WooCommerce login and registration form
 * - Add repeat email field to register form in my-acount page
 * - Add terms checkbox to register form in my-acount page
 * - Disable auto-login after sucessful registration
 * - Force redirect to 'my account' page after registration
 * - Add repeat email field and remove phone in the billing form in checkout
 *   page
 */
// Add repeat email field and terms checkbox
function tlc_woocommerce_register_form() {
    ?>
    <p class="form-row form-row-wide">
	    <label for="reg_password2"><?php _e( 'Confirm email address', 'thelittlecraft' ); ?> <span class="required">*</span></label>
	    <input type="email" class="input-text" name="email2" id="reg_email2" value="<?php if ( ! empty( $_POST['email2'] ) ) echo esc_attr( $_POST['email2'] ); ?>" />
    </p>
    <?php
    wc_get_template( 'checkout/terms.php' );
}
add_action( 'woocommerce_register_form', 'tlc_woocommerce_register_form' );

function tlc_validation_registration( $error, $username, $password, $email ) {
    if ( 'no' === get_option( 'woocommerce_registration_generate_username' ) && empty( $username ) ) {
    	$error->add('error', __( 'Please enter a valid account username.', 'thelittlecraft' ) );
    }
    elseif ( empty( $email ) ) {
	$error->add('error', __( 'Please provide a valid email address.', 'thelittlecraft' ) );
    }
    elseif ( ! isset( $_POST['email2'] ) || empty( $_POST['email2'] ) ) {
	$error->add('error', __( 'Please confirm your email address by re-typing your email address.', 'thelittlecraft' ) );
    }
    elseif ( strcmp( $email, $_POST['email2'] ) !== 0 ) {
	$error->add('error', __( 'The email addresses do not match.', 'thelittlecraft' ) );
    }
    elseif ( 'no' === get_option( 'woocommerce_registration_generate_password' ) && empty( $password ) ) {
	$error->add('error', __( 'Please enter an account password.', 'thelittlecraft' ) );
    }
    elseif ( ! isset($_POST['terms']) || empty( $_POST['terms'] ) ) {
	$error->add('error', __( 'You must accept our Terms &amp; Conditions.', 'thelittlecraft' ) );
    }
    return $error;
}
add_filter( 'woocommerce_process_registration_errors', 'tlc_validation_registration', 10, 4 );

// Disable auto-login after sucessful registration
function tlc_woocommerce_registration_disable_auto_login_new_customer( $true, $new_customer ) {
    return false;
}
add_filter('woocommerce_registration_auth_new_customer', 'tlc_woocommerce_registration_disable_auto_login_new_customer', 10, 2 );

// Force redirect to 'my account' page after registration
function tlc_woocommerce_registration_redirect( $var ) {
    // Add registration success message
    wc_add_notice( __('Thanks for registering with us. Your <strong>password</strong> was emailed to you.<br>Check your email inbox and return to this page to login with your username and password.', 'thelittlecraft'), 'success' );
    wc_add_notice( __('<strong>Notice:</strong> If you don&rsquo;t find the email with the password in your inbox, check your spam or junk folder.', 'thelittlecraft'), 'notice' );

    return wc_get_page_permalink( 'myaccount' );
}
add_filter( 'woocommerce_registration_redirect', 'tlc_woocommerce_registration_redirect', 10, 1 );

// Add repeat email field to billing form in checkout page
function tlc_woocommerce_checkout_fields( $fields ) {
    // Remove Phone field
    unset( $fields['billing']['billing_phone'] );
    // Make Email field wide
    $fields['billing']['billing_email']['class'] = array('form-row-wide');
    $fields['billing']['billing_email']['clear'] = true;

    $billing_email2 = array(
	'type'		=> 'email',
	'label'		=> __( 'Confirm Email Address', 'thelittlecraft' ),
	'placeholder'	=> _x( 'Retype Email Address', 'placeholder', 'thelittlecraft' ),
	'class'		=> array('form-row-wide', 'validate-email' ),
	'required'	=> true,
	'clear'		=> true
    );

    $index = 1;
    foreach( $fields['billing'] as $key => $field ) {
	if ( strcmp( $key, 'billing_email' ) == 0 ) {
	    break;
	} else {
	    $index++;
	}
    }

    $fields['billing'] = array_slice( $fields['billing'], 0, $index, true ) + array( 'billing_email-2' => $billing_email2 ) + array_slice( $fields['billing'], $index, count( $fields['billing'] ) - 1, true );

    return $fields;
};
add_filter( 'woocommerce_checkout_fields', 'tlc_woocommerce_checkout_fields', 10, 1 );

// Remove Phone field also from edit billing address under my account
function tlc_woocommerce_billing_fields( $fields ) {
    unset( $fields['billing_phone'] );
    return $fields;
}
add_filter( 'woocommerce_billing_fields' , 'tlc_woocommerce_billing_fields', 10 , 1 );

function tlc_custom_checkout_field_process() {
    if ( isset( $_POST['billing_email'] ) && ! empty( $_POST['billing_email'] ) && isset( $_POST['billing_email-2'] ) && ! empty( $_POST['billing_email-2'] ) && strcmp( $_POST['billing_email'], $_POST['billing_email-2'] ) !== 0 ) {
	wc_add_notice( __( 'The <strong>Email Address</strong> and <strong>Confirm Email Address</strong> do not match.' ), 'error' );
    }
}
add_action('woocommerce_checkout_process', 'tlc_custom_checkout_field_process', 10 );


/**
 * Return page permalink by id translated to current language
 * ToDo: if page id doesn't exist what is the return value of pll_get_post()?
 */
function tlc_pll_get_permalink( $page_id ) {
    if ( function_exists( 'pll_get_post' ) )
        return get_permalink( pll_get_post( $page_id ) );
    else
	return false;
}


/**
 * Remove footer Credit, add Imprint and T&C and Privacy Policy
 * ToDo: if page id doesn't exist what is the return value of pll_get_post()?
 */
function tlc_footer_credit() {
    remove_action( 'storefront_footer', 'storefront_credit', 20 );
    add_action( 'storefront_footer', 'tlc_storefront_footer', 20 );
}

function tlc_storefront_footer() {
    if ( function_exists( 'pll_get_post' ) ) {

	/* footer pages */
	$privacy_en_page_id = 79;
	$imprint_en_page_id = 80;
	$terms_en_page_id = 81;

	$privacy_tr_id = pll_get_post( $privacy_en_page_id );
	$imprint_tr_id = pll_get_post( $imprint_en_page_id );
	$terms_tr_id = pll_get_post( $terms_en_page_id );

	?>
	<div class="site-info">
	    <div class="copyright">Copyright &copy; <?php echo ' '.get_the_date('Y').' '.get_bloginfo('name'); ?></div>
	    <div class="terms">
		<span><a href="<?php echo get_page_link( $terms_tr_id ); ?>"><?php echo get_the_title( $terms_tr_id ); ?></a></span>
		<span><a href="<?php echo get_page_link( $privacy_tr_id ); ?>"><?php echo get_the_title( $privacy_tr_id ); ?></a></span>
		<span><a href="<?php echo get_page_link( $imprint_tr_id ); ?>"><?php echo get_the_title( $imprint_tr_id ); ?></a></span>
	    </div>
	</div><!-- .site-info -->
	<?php
    }
}
add_action( 'init', 'tlc_footer_credit', 10 );


/**
 * Add Required field note to forms
 * - Login and Register forms
 * - Edit account
 * - Edit billing address
 * - Edit shipping address
 * - Checkout
 * - Comments (and ratings)
 */
function tlc_add_required_field_note() {
    ?>
    <p><span class="required">*</span><em><?php _e( '&nbsp;Required field', 'thelittlecraft' ); ?></em></p>
    <?php
}
add_action( 'woocommerce_after_customer_login_form', 'tlc_add_required_field_note' );
add_action( 'woocommerce_edit_account_form_end', 'tlc_add_required_field_note' );
add_action( 'woocommerce_after_edit_address_form_billing', 'tlc_add_required_field_note' );
add_action( 'woocommerce_after_edit_address_form_shipping', 'tlc_add_required_field_note' );
add_action( 'woocommerce_after_checkout_form', 'tlc_add_required_field_note' );
add_action( 'comment_form_after', 'tlc_add_required_field_note' );


/**
 * Adds a top bar to Storefront, before the header.
 */
function tlc_storefront_add_topbar() {
    ?>
    <div id="tlc-top-bar" class="tlc-top-bar-wrap show-on-mobile">
	<div class="col-full">
	    <section class="tlc-top-bar fix">
		<section class="tlc-top-bar col-1 block">
		    <!-- content goes here -->
		    <?php
		    if ( function_exists( 'pll_the_languages' ) ) {
			?>
			<aside class="tlc_polylang_language_switcher">
			    <ul><?php pll_the_languages( array( 'show_flags' => 1, 'hide_current' => 1 ) ); ?></ul>
			</aside>
			<?php
		    }
		    ?>
		</section>
		<div class="clear"></div>
	    </section>
	</div>
    </div>
    <?php
}
add_action( 'storefront_before_header', 'tlc_storefront_add_topbar' );


/**
 * Adds a Store wide notice, before the header.
 */
if ( function_exists( 'pll_register_string' ) ) {
    pll_register_string( 'announcement', 'SPECIAL OFFER: Free shipping for Germany for orders above 29 €!', 'thelittlecraft' );
}
function tlc_storefront_add_shop_notice() {
    if ( function_exists( 'pll__' ) ) {
	?>
	<div id="tlc-announcement" class="tlc-announcement green show-on-mobile">
	    <span><?php pll_e( 'SPECIAL OFFER: Free shipping for Germany for orders above 29 €!' ); ?></span>
	</div>
	<?php
    }
}
add_action( 'storefront_before_header', 'tlc_storefront_add_shop_notice', 1 );


/**
 * Replace WooCommerce default PayPal icon
 */
function tlc_paypal_checkout_icon() {
 return get_stylesheet_directory_uri() . '/images/paypal_credit_cards.png'; // write your own image URL here
}
add_filter( 'woocommerce_paypal_icon', 'tlc_paypal_checkout_icon' );


/**
 * Small Business VAT Notice
 */
// Register the vat notice strings for translation
if ( function_exists( 'pll_register_string' ) ) {
    // The string is used before the footer in all pages
    pll_register_string( 'small_business_vat_notice_footer', '* All stated prices are final prices excl. <a href="%s" target="_blank">Shipping</a>, exempt from VAT according to Art. 19 of the German VAT law (UStG).', 'thelittlecraft' );
    // The string is used in the Credit Note template in the Customer Messages block
    pll_register_string( 'small_business_vat_notice_credit_note', 'The invoiced amount is exempt from value-added tax according to Art. 19 (1) of the German VAT law (UStG).', 'thelittlecraft' );
    // The string is used in the single product overview page and product listings
    pll_register_string( 'small_business_vat_notice_product', 'Final Price excl. <a href="%s" target="_blank">Shipping</a>, exempt from VAT according to Art. 19 of the German VAT law (UStG)', 'thelittlecraft' );
}

// Add vat notice before footer in all pages
function tlc_small_business_vat_notice_footer() {
    if ( function_exists( 'pll__' ) ) {
	?>
	<div id="small_business_vat_notice_footer">
	    <div class="col-full">
		<em><?php printf( pll__( '* All stated prices are final prices excl. <a href="%s" target="_blank">Shipping</a>, exempt from VAT according to Art. 19 of the German VAT law (UStG).' ), esc_url( tlc_pll_get_permalink( 83 ) ) ); ?></em>
	    </div>
	</div>
	<!-- #small_business_vat_notice_footer -->
	<?php
    }
}
add_action( 'storefront_before_footer', 'tlc_small_business_vat_notice_footer', 50 );

// Add vat notice to single product overview page and product listings
function overwrite_woocommerce_price_format( $price, $instance ) {
    if ( function_exists( 'pll__' ) ) {
	$content = '<small class="small_business_vat_notice">';
	$content .= sprintf( pll__( 'Final Price excl. <a href="%s" target="_blank">Shipping</a>, exempt from VAT according to Art. 19 of the German VAT law (UStG)'), esc_url( tlc_pll_get_permalink( 83 ) ) );
	$content .= '</small>';

	$price = $price . $content;
    }
    return $price;
}
add_filter( 'woocommerce_price_html', 'overwrite_woocommerce_price_format', 10, 2 );
add_filter( 'woocommerce_sale_price_html', 'overwrite_woocommerce_price_format', 10, 2 );
add_filter( 'woocommerce_grouped_price_html', 'overwrite_woocommerce_price_format', 10, 2 );
add_filter( 'woocommerce_variable_price_html', 'overwrite_woocommerce_price_format', 10, 2 );
add_filter( 'woocommerce_variable_sale_price_html', 'overwrite_woocommerce_price_format', 10, 2 );
add_filter( 'woocommerce_variation_price_html', 'overwrite_woocommerce_price_format', 10, 2 );
add_filter( 'woocommerce_variation_sale_price_html', 'overwrite_woocommerce_price_format', 10, 2 );

// Remove product price, in the shop loop, from linking to the product overview page.
// The VAT Notice contain a link and HTML doesn't allow a link inside a link.
remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_product_link_close', 5 );
add_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_product_link_close', 9 );


/**
 * Register Storefront Parallax Hero front-end strings
 */
if ( function_exists( 'pll_the_languages' ) ) {
    pll_register_string( 'Heading text', 'Heading text', 'Storefront Parallax Hero Polylang fix' );
    pll_register_string( 'Description text', 'Description text', 'Storefront Parallax Hero Polylang fix' );
    pll_register_string( 'Button text', 'Button text', 'Storefront Parallax Hero Polylang fix' );
    pll_register_string( 'Button url', 'Button url', 'Storefront Parallax Hero Polylang fix' );
}


/**
 *  Hide Standard Shipping option when free shipping is available
 */
function tlc_hide_shipping_when_free_is_available( $rates, $package ) {

    // Only modify rates if free_shipping is present
    if ( isset( $rates['free_shipping'] ) ) {
	// To unset a single rate/method, do the following. This example unsets flat_rate shipping
	//unset( $rates['flat_rate'] );

	// To unset all methods except for free_shipping, do the following
	$free_shipping          = $rates['free_shipping'];
	$rates                  = array();
	$rates['free_shipping'] = $free_shipping;
    }

    return $rates;
}
add_filter( 'woocommerce_package_rates', 'tlc_hide_shipping_when_free_is_available', 10, 2 );


/**
 * Fix Price Display Suffix not translated by Woo_Poly
 */
// Register the string price_display_suffix to be translated
if ( function_exists( 'pll_the_languages' ) ) {
    pll_register_string( 'price_display_suffix', get_option( 'woocommerce_price_display_suffix' ), 'thelittlecraft' );
}

function tlc_translate_price_display_suffix( $string, $instance ) {
    if ( function_exists( 'pll__' ) ) {
	$price_display_suffix = get_option( 'woocommerce_price_display_suffix' );
	return str_replace( $price_display_suffix, pll__( $price_display_suffix ), $string );
    }
}
add_filter( 'woocommerce_get_price_suffix', 'tlc_translate_price_display_suffix', 10, 2 );


/**
 * Fix Customer Credit Note emails not sent on the order locale
 */
function tlc_replace_class_wc_email_customer_credit_note() {
    if ( class_exists( 'WooCommerce_PDF_IPS_Pro' ) ) {
	// Overrides the Email Customer Credit Note class
	function tlc_override_class( $email_classes ) {

	    // add our custom email classe to the list of email classes that WooCommerce loads
	    // overiding the plugin email class
	    if ( version_compare( WOOCOMMERCE_VERSION, '2.2', '>=' ) ) {
		    $email_classes['WC_Email_Customer_Credit_Note'] = include( 'includes/tlc_email-customer-credit-note.php' );
	    }
	    return $email_classes;
	}
	add_filter( 'woocommerce_email_classes', 'tlc_override_class' );
    }
}
add_action( 'woocommerce_init', 'tlc_replace_class_wc_email_customer_credit_note' );


/**
 * Fix PDF Invoices and Customer Credit Note emails and template settings not sent on the order locale
 */
// Helper functions: Get text translations
function tlc_get_shop_name_text( $shop_name ) {
    return tlc_get_text_translation( 'shop_name', $shop_name );
}

function tlc_get_shop_address_text( $shop_address ) {
    return tlc_get_text_translation( 'shop_address', $shop_address );
}

function tlc_get_footer_text( $footer ) {
    return tlc_get_text_translation( 'footer', $footer );
}

function tlc_get_extra_1_text( $extra_1 ) {
    return tlc_get_text_translation( 'extra_1', $extra_1 );
}

function tlc_get_extra_2_text( $extra_2 ) {
    return tlc_get_text_translation( 'extra_2', $extra_2 );
}

function tlc_get_extra_3_text( $extra_3 ) {
    return tlc_get_text_translation( 'extra_3', $extra_3 );
}

function tlc_get_text_translation( $string_name, $default ) {
    global $wpo_wcpdf;

    // we don't rely on $default, it has been filtered throught wpautop &
    // wptexturize when the apply_filter function was invoked
    $string = pll_translate_string( $wpo_wcpdf->settings->template_settings[$string_name], $wpo_wcpdf->order_language );
    return wpautop( wptexturize( $string ) );
}

function tlc_translate_wpo_wcpdf_template_settings( $template_type, $id ) {
    if ( class_exists( 'Polylang' ) && class_exists( 'WooCommerce_PDF_Invoices' ) ) {

	// Excludes calls from my-account page - here the current viewing language is used
	if ( ! isset( $_GET['my-account'] ) ) {

	    global $wpo_wcpdf, $locale, $wp_locale, $polylang, $woocommerce;
	    static $cache; // Polylang string translations cache object to avoid loading the same translations object several times

	    // Is a refund?
	    if ( get_post_type( $id ) == 'shop_order_refund' && $parent_order_id = wp_get_post_parent_id( $id ) ) {
		$order_id = $parent_order_id;
	    }
	    else {
		$order_id = $id;
	    }

	    // Cache object not found. Create one...
	    if ( empty( $cache ) ) {
		$cache = new PLL_Cache();
	    }

	    // get order language
	    $order_language = pll_get_post_language( $order_id, 'locale' );
	    if ( $order_language == '' ) {
		    // fallback to default
		    $order_language = pll_default_language( 'locale' );
	    }

	    // add language information to plugin object
	    $wpo_wcpdf->order_language = $order_language;
	    $wpo_wcpdf->current_language = pll_current_language( 'locale' );

	    // unload plugin's textdomains
	    unload_textdomain( 'default' );
	    unload_textdomain( 'woocommerce' );

	    // set locale to order locale
	    $locale = apply_filters( 'locale', $order_language );
	    $polylang->curlang->locale = $order_language;

	    // Cache miss
	    if (false === $mo = $cache->get( $order_language ) ) {
		$mo = new PLL_MO();
		$mo->import_from_db( $GLOBALS['polylang']->model->get_language( $order_language ) );
		$GLOBALS['l10n']['pll_string'] = &$mo;

		// Add to cache
		$cache->set( $order_language, $mo );
	    }

	    // (re-)load plugin's textdomain in order locale
	    load_default_textdomain( $order_language );
	    $woocommerce->load_plugin_textdomain();

	    $wp_locale = new WP_Locale();

	    // filter admin texts to explicitly call polylang for each admin string
	    add_filter( 'wpo_wcpdf_shop_name', 'tlc_get_shop_name_text', 9, 1 );
	    add_filter( 'wpo_wcpdf_shop_address', 'tlc_get_shop_address_text', 9, 1 );
	    add_filter( 'wpo_wcpdf_footer', 'tlc_get_footer_text', 9, 1 );
	    add_filter( 'wpo_wcpdf_extra_1', 'tlc_get_extra_1_text', 9, 1 );
	    add_filter( 'wpo_wcpdf_extra_2', 'tlc_get_extra_2_text', 9, 1 );
	    add_filter( 'wpo_wcpdf_extra_3', 'tlc_get_extra_3_text', 9, 1 );

	}
    }
}
add_action( 'wpo_wcpdf_process_template_order', 'tlc_translate_wpo_wcpdf_template_settings', 10, 2 );


/**
 * Get additional refreshed fragments
 *
 * This is required to update via ajax parts of the pages that are cached
 *
 * @param array $fragments	Array containing fragments to be updated
 * @return array		Updated list of fragments
 */
function tlc_get_additional_refreshed_fragments( $fragments ) {

    /**
     * Get the HTML for the recently viewed products widget
     *
     * @param string	$widget_id	The widget id registered in Wordpress
     * @param string	$title		The widget title configured for this
     * 					widget's instance
     * @param int	$number		Number of last product viewed configured
     * 					for this widget's instance
     * @param string	$css_class	CSS classes configured for this widget's
     * 					instance. Default = widget default
     * 	@return string			Widget's HTML
     */
    function tlc_get_wc_recently_viewed_product_html( $widget_id, $title, $number = 10, $css_class = 'woocommerce widget_recently_viewed_products' ) {

	/**
	* Short the products ids list by the order of recently viewed. The order
	* by which the products have been viewed is stored in a cookie passed in
	* the $order variable.
	*
	* @param array	$product	List of product ids. This is the list of
	* 				products return by the wp_query. It is a
	* 				subset of the products viewed, as 'out
	* 				of stock', meanwhile deleted products,
	* 				etc have been filtered out.
	* @param array	$order		Order by which the filtered products shall
	* 				be shorted.This is a list of product ids,
	* 				as they are stored in the cookie.
	* @return array			Sorted list of product ids.
	*/
	function tlc_recently_viewed_short_time_asc( $products, $order ) {
	    // Nothing to do here!
	    if ( empty( $products ) && empty( $order ) ) {
		return;
	    }

	    // Holds the array of post objects ordered by the view order
	    $products_order_view = array();

	    // Re-short the posts per view order
	    foreach( $order as $o ) {
		foreach( $products as $p ) {
		    if ( $p->ID == $o )
			$products_order_view[] = $p;
		}
	    }

	    return $products_order_view;
	}

	$viewed_products = ! empty( $_COOKIE['woocommerce_recently_viewed'] ) ? (array) explode( '|', $_COOKIE['woocommerce_recently_viewed'] ) : array();
	$viewed_products = array_filter( array_map( 'absint', $viewed_products ) );

	if ( ! $widget_id || empty( $viewed_products ) ) {
	    return;
	}

	// The woocommerce_recently_viewed cookie holds all products viewed independent
	// of the language. Product in other language are converted to the current
	// language equivalent. Products without a equivalent are omitted.
	if ( function_exists( 'pll_get_post' ) ) {
	    $translated_products = array();
	    foreach( $viewed_products as $product ) {
		$translated_products[] = pll_get_post( $product );
	    }
	    $translated_products = array_filter( $translated_products ); // Remove empty entries
	}
	else
	    $translated_products = $viewed_products;

	$translated_products = array_unique( $translated_products ); // Remove duplicates
	$translated_products = array_values( $translated_products ); // Reset array keys
	$translated_products = array_slice( $translated_products, -1 * abs($number), abs($number) ); // Leave the last $numer entries

       if ( empty( $translated_products ) ) {
	    return;
	}

	// Start the wp_query
	ob_start();

	$query_args = array( 'posts_per_page' => $number, 'no_found_rows' => 1, 'post_status' => 'publish', 'post_type' => 'product', 'post__in' => $translated_products, 'orderby' => 'rand' );
	$query_args['meta_query']   = array();
	$query_args['meta_query'][] = WC()->query->stock_status_meta_query();
	$query_args['meta_query']   = array_filter( $query_args['meta_query'] );

	$r = new WP_Query( $query_args );

	if ( $r->have_posts() ) {

	    // Re-short the posts per view order
	    $r->posts = tlc_recently_viewed_short_time_asc( $r->posts, $translated_products );

	    ?>
	    <!-- Refreshed fragment -->
	    <aside id="<?php echo $widget_id ?>" class="widget <?php echo $css_class ?>">
		<h3 class="widget-title"><?php function_exists( 'pll_e' ) ? pll_e( $title ) : $title; ?></h3>
		<ul class="product_list_widget">
		<?php

		while ( $r->have_posts() ) {
		    $r->the_post();
		    wc_get_template( 'content-widget-product.php' );
		}

		?>
		</ul>
	    </aside>
	    <!-- End: Refreshed fragment -->
	    <?php
	}

	return ob_get_clean();
    }

    global $wp_registered_widgets;

    // Loop over each registered widget so we can fetch the data out of the wp_options table.
    foreach ( $wp_registered_widgets as $widget ) {

	// Searches widgets with dynamic contents by id_base
	switch( $widget['callback'][0]->id_base ) {

	    // Ensure recently viewed product widget contents update
	    case 'woocommerce_recently_viewed_products':
		// Move on if widget not active
		if ( ! is_active_widget( false, $widget['id'], 'woocommerce_recently_viewed_products', true ) )
		    continue;

		// The name of the option in the database is the name of the widget class.
		$option_name = $widget['callback'][0]->option_name;
		$widget_data = get_option($option_name);

		// Widget data is stored as an associative array. To get the right data we need to get the right key which is stored in $widget['params'][0]['number']
		$key = $widget['params'][0]['number'];

		// Number of recently viewed products configured for this widget
		$number = $widget_data[$key]['number'];

		// Title Configured for this widget
		$title = $widget_data[$key]['title'];

		// CSS classes
		$css_class = $widget['classname'];

		$fragments['#' . $widget['id']] = tlc_get_wc_recently_viewed_product_html( $widget['id'], $title, $number, $css_class );
		break;

	    // Add additional cases to refresh other widgets
	    default:
		break;
	}

    }

    // Add other elements you want to refresh here

    return $fragments;
}
// READ ME: Only uncomment if you use a cache plugin and want to refresh parts
// of the page via js. If you uncomment the action below, uncomment also
// 'tlc_track_product_view_javascript' above, and the code snippet in the
// js/tlc_custom_js.js and enqueue the theme child js with priority set to 9.
add_filter( 'woocommerce_add_to_cart_fragments', 'tlc_get_additional_refreshed_fragments', 10, 1 );


/**
 * Insert a javascript code snippet in the single product pages, if the widget
 * 'woocommercer_recently_viewed_products' is active, that sets javascript vars
 * that will trigger a JQuery code snippet that updates the cookie holding the
 * list of recently viewed products ids, in the event the user is served
 * a cached paged. The cached page could have been generated during other user's
 * visit, and therefore containing his/hers list of recently viewed product ids.
 * Without this code snippet, the cookie would not be updated.
 * The code snipped inserts also a hidden placeholder for the widget HTML, if not
 * presen. The placeholder will be replaced by the refreshed fragment return via
 * ajax (see function tlc_get_additional_refreshed_fragments() for details). This
 * avoid, that no widget is printed, if the cached page was generated upon the first
 * product view.
 */
function tlc_track_product_view_javascript() {
    if ( ! is_singular( 'product' ) || ! is_active_widget( false, false, 'woocommerce_recently_viewed_products', true ) ) {
	return;
    }

    global $wp_registered_widgets, $post;

    foreach ( $wp_registered_widgets as $widget ) {
	// Search for active recently viewed product widgets
	if ( 'woocommerce_recently_viewed_products' ==  $widget['callback'][0]->id_base && is_active_widget( false, $widget['id'], 'woocommerce_recently_viewed_products', true ) ) {
	    $widget_id = $widget['id'];
	}
    }

    ?>
    <script type="text/javascript">
	/* <![CDATA[ */
	    var tlc_widget_id = '<?php echo $widget_id; ?>';
	    var tlc_product_id = '<?php echo $post->ID; ?>';
	    var tlc_wc_recently_viewed_products_cookie = {
		name: 'woocommerce_recently_viewed',
		path: '<?php echo COOKIEPATH ? COOKIEPATH : '/'; ?>',
		domain: '<?php echo COOKIE_DOMAIN ? COOKIE_DOMAIN : ''; ?>'
	    }
	/* ]]> */
    </script>
    <?php
}
// READ ME: Only uncomment if you use a cache plugin and want to refresh parts
// of the page via js. If you uncomment the action below, uncomment also
// 'tlc_get_additional_refreshed_fragments' above, and the code snippet in the
// js/tlc_custom_js.js and enqueue the theme child js with priority set to 9.
add_action( 'wp_footer', 'tlc_track_product_view_javascript' );


/**
 * Remove WooCommerce SKUs Only on Product Pages
 */
function tlc_remove_product_page_skus( $enabled ) {
    if ( ! is_admin() && is_product() ) {
        return false;
    }

    return $enabled;
}
add_filter( 'wc_product_sku_enabled', 'tlc_remove_product_page_skus' );


/**
 *Reduce the strength requirement on the woocommerce password.
 *
 * Strength Settings
 * 3 = Strong (default)
 * 2 = Medium
 * 1 = Weak
 * 0 = Very Weak / Anything
 */
function tlc_reduce_woocommerce_min_strength_requirement( $strength ) {
    return 1;
}
add_filter( 'woocommerce_min_password_strength', 'tlc_reduce_woocommerce_min_strength_requirement' );


/**
 * Events Maker
 */
// Set content wrapper for storefront theme
function tlc_em_strorefront_content_wrapper_start( $output, $template ){
    if ( $template === 'storefront' ) {
	$output = '<div id="primary" class="content-area"><main id="main" class="site-main" role="main">';
    }
    return $output;
}
add_filter( 'em_content_wrapper_start', 'tlc_em_strorefront_content_wrapper_start', 10, 2 );

function tlc_em_strorefront_content_wrapper_end( $output, $template ){
    if ( $template === 'storefront' ) {
	$output = '</main></div>';
    }
    return $output;
}
add_filter( 'em_content_wrapper_end', 'tlc_em_strorefront_content_wrapper_end', 10, 2 );

// Events Maker add and remove actions
function tlc_em_add_and_remove_actions() {
    remove_action( 'em_before_main_content', 'em_breadcrumb', 20 );
    add_action( 'em_after_single_event_title', 'tlc_em_add_buy_tickets_button', 50 );
}

function tlc_em_add_buy_tickets_button() {
    $post = get_post();

    if ( ! empty( $post ) ) {
	$event_product_id = get_post_meta( $post->ID, 'event_product_id', true );
	echo '<div class="entry-meta entry-tickets buy-tickets"><a href="' . get_permalink( (int) $event_product_id ) . '" class="button">' . __( 'Buy Tickets', 'thelittlecraft' ) . '</a></div>';
    }
}
add_action( 'wp', 'tlc_em_add_and_remove_actions' );

// Single Event page - Set event thumbnails same size as product's size
function tlc_em_single_event_thumnail_size() {
    return 'tlc_event_image_size';
}
add_image_size( 'tlc_event_image_size', 800, 400, true ); // NOTE: changing the size requires to regenerate your thumbnails
add_filter( 'em_single_event_thumbnail_size', 'tlc_em_single_event_thumnail_size', 10 );
add_filter( 'em_loop_event_thumbnail_size', 'tlc_em_single_event_thumnail_size', 10 );

// Single Event page - Replace ligthbox with prettyphoto in the event imagery
function tlc_em_replace_lightbox_with_prettyphoto( $hmtl, $post_id ) {

    $event_gallery = get_post_meta( $post_id, '_event_gallery', true );

    if ( !empty( $event_gallery ) )
	return str_replace( 'rel="lightbox"', 'data-rel="prettyPhoto[' . $post_id . ']"', $hmtl );
    else
	return str_replace( 'rel="lightbox"', 'data-rel="prettyPhoto"', $hmtl );
}
add_filter( 'em_single_event_thumbnail_html', 'tlc_em_replace_lightbox_with_prettyphoto', 10, 2 );
add_filter( 'em_event_gallery_thumbnail_html', 'tlc_em_replace_lightbox_with_prettyphoto', 10, 2 );

// Exclude Event Product from the woocommerce loop
function tlc_custom_pre_get_posts_query( $q ) {
    if ( ! $q->is_main_query() ) return;
    if ( ! $q->is_post_type_archive() ) return;

    if ( ! is_admin() && is_shop() ) {
	$q->set( 'tax_query', array( array(
	    'taxonomy' => 'product_cat',
	    'field' => 'slug',
	    'terms' => array( 'events' ), // Don't display products in the events (slug) category on the shop page
	    'operator' => 'NOT IN'
	) ) );
    }
    remove_action( 'pre_get_posts', 'tlc_custom_pre_get_posts_query' );
}
add_action( 'pre_get_posts', 'tlc_custom_pre_get_posts_query' );


/**
 * Alo EasyMail Newsletter plugin customisations
 */
function tlc_easymail_auto_add_subscriber_to_list( $subscriber, $user_id = false ) {
        global $post;

	//error_log(print_r($post, true));
	//is_singular() ? error_log(print_r('is singular', true)) : error_log(print_r(is_singular(), true));
	//is_object( $post ) ? error_log(print_r('is object', true)) : error_log(print_r(is_object( $post ), true));

	/* TODO find a way to get the page id outside of the Loop
	 * EG: http://wordpress.stackexchange.com/questions/166106/how-to-get-post-id-of-the-current-page-post-inside-a-widget
	if ( is_singular() && is_object( $post ) ) {
                switch( $post->ID ) {
                        // My Account and Mein Konto pages
                        case 11:
                        case 58:
                                $list_id = 2; // Registered User List
                                break;

                        // etc etc ........

                        // Anywhere from the Footer Widget
                        default:
                                $list_id = 1; // Subscriber Default List
                }
                alo_em_add_subscriber_to_list ( $subscriber->ID, $list_id );
        }*/
	alo_em_add_subscriber_to_list ( $subscriber->ID, 1 );
}
add_action( "alo_easymail_new_subscriber_added",  "tlc_easymail_auto_add_subscriber_to_list", 10, 2 );
