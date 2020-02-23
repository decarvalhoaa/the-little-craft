<?php
/**
 * The Little Craft template functions.
 */

/**
 * Sets custom theme colours.
 *
 * @since 2.0.0
 *
 * @param array $args 	Storefront (default) theme colours.
 *
 * @return array 		Custom The Little Craft theme colours.
 */
function tlc_set_storefront_setting_values( $args ) {
	$new_args = array(
        'storefront_heading_color'           		=> '#ee626a',
		'storefront_text_color'              		=> '#5f6062',
		'storefront_accent_color'            		=> '#ee626a',
		'storefront_hero_heading_color'      		=> '#000000',
		'storefront_hero_text_color'         		=> '#000000',
		'storefront_header_background_color' 		=> '#ffffff',
		'storefront_header_text_color'       		=> '#93979f',
		'storefront_header_link_color'       		=> '#5f6062',
		'storefront_footer_background_color' 		=> '#fde7dd',
		'storefront_footer_heading_color'    		=> '#d54942',
		'storefront_footer_text_color'       		=> '#5f6062',
		'storefront_footer_link_color'       		=> '#ee6260',
		'storefront_button_background_color' 		=> '#fde7dd',
		'storefront_button_text_color'       		=> '#ee626a',
		'storefront_button_alt_background_color'	=> '#5f6062',
		'storefront_button_alt_text_color'   		=> '#ffffff',
		'storefront_layout'                  		=> 'right',
		'background_color'                   		=> 'f9f9f9'
    );
    $args = array_merge( $args, $new_args );

    return $args;
}

/**
 * Display the small business VAT notice before footer in all pages.
 */
function tlc_small_business_vat_notice_footer() {
	$shipping_page_slug = 'lieferung';
	$shipping_page = tlc_get_page_by_slug( $shipping_page_slug );
	
	?>
	<div id="tlc_small_business_vat_notice_footer" class="tlc_small_business_vat_notice_footer">
	    <div class="col-full">
			<em>
				<?php
				if ( !is_null( $shipping_page ) )
					printf( __( '* All stated prices are final prices excl. <a href="%s">Shipping</a>, exempt from VAT according to Art. 19 of the German VAT law (UStG).', 'thelittlecraft' ), esc_url( get_page_link( $shipping_page ) ) );
				else 
					echo( __( '* All stated prices are final prices excl. Shipping, exempt from VAT according to Art. 19 of the German VAT law (UStG).', 'thelittlecraft' ) );
				?>
			</em>
	    </div>
	</div>
	<!-- #small_business_vat_notice_footer -->
	<?php
}

/**
 * Adds VAT notice to product price on single product overview page and product listings.
 * 
 * @param  string 		HTML with product price.
 * @param  WP_Product 	Product object.
 * 
 * @return string 		Filtered string.
 */
function tlc_display_product_price_vat_notice( $price, $instance ) {
	$shipping_page_slug = 'lieferung';
	$shipping_page = tlc_get_page_by_slug( $shipping_page_slug );

	$content = '<span class="small_business_vat_notice">';
	if ( !is_null( $shipping_page ) )
		$content .= sprintf( __( 'Final Price excl. <a href="%s">Shipping</a>, exempt from VAT according to Art. 19 of the German VAT law (UStG)', 'thelittlecraft' ), esc_url( get_page_link( $shipping_page ) ) );
	else
		$content .= __( 'Final Price excl. Shipping, exempt from VAT according to Art. 19 of the German VAT law (UStG)', 'thelittlecraft' );
	$content .= '</span>';

	$price = $price . $content;
    
    return $price;
}

/**
 * Remove product price, in the shop loop, from linking to the product overview page. Added after the product title.
 * The VAT notice contain a link and HTML doesn't allow a link inside a link.
 *
 * @since 1.1.0
 */
function tlc_small_business_vat_notice_price_link_fix() {
	remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_product_link_close', 5 );
	add_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_product_link_close', 9 );
}

/**
 * Replaces Storefront footer credit with The Little Craft custom credit 
 * with links to the Imprint, T&C and Privacy Policy pages.
 *
 * @since 1.1.0
 */
function tlc_footer_credit() {
    remove_action( 'storefront_footer', 'storefront_credit', 20 );
    add_action( 'storefront_footer', 'tlc_storefront_footer', 20 );
}

/**
 * Display custom footer credit.
 */
function tlc_storefront_footer() {
	/* footer linked pages */
	$privacy_page_slug = 'datenschutz';
	$imprint_page_slug = 'impressum';
	$terms_page_slug   = 'agb';
	
	$privacy_page = tlc_get_page_by_slug( $privacy_page_slug );
	$imprint_page = tlc_get_page_by_slug( $imprint_page_slug );
	$terms_page   = tlc_get_page_by_slug( $terms_page_slug );

	?>
	<div class="site-info">
		<div class="copyright">Copyright &copy; <?php echo ' '.get_the_date('Y').' '.get_bloginfo('name'); ?></div>
		<div class="terms">
			<?php
			if ( !is_null( $terms_page ) )
				printf( '<span><a href="%s">%s</a></span>', get_page_link( $terms_page ), get_the_title( $terms_page ) );
			if ( !is_null( $privacy_page ) )
				printf( '<span><a href="%s">%s</a></span>', get_page_link( $privacy_page ), get_the_title( $privacy_page ) );
			if ( !is_null( $imprint_page ) )
				printf( '<span><a href="%s">%s</a></span>', get_page_link( $imprint_page ), get_the_title( $imprint_page ) );
			?>
		</div>
	</div><!-- .site-info -->
	<?php
}

/**
 * Display terms checkbox on the registration form in My Account page
 *
 * @since 1.1.0
 */
function tlc_register_form_terms() {
	// Remove the privacy policy text specific for checkout from the terms checkout template
	remove_action( 'woocommerce_checkout_terms_and_conditions', 'wc_checkout_privacy_policy_text', 20 );
	wc_get_template( 'checkout/terms.php' );
}

/**
 * Display confirm email input field in the registration form in My Account page.
 */
function tlc_woocommerce_register_form_email2() {
    ?>
    <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
	    <label for="reg_email2"><?php esc_html_e( 'Confirm email address', 'thelittlecraft' ); ?>&nbsp;<span class="required">*</span></label>
	    <input type="email" class="woocommerce-Input woocommerce-Input--text input-text" name="email2" id="reg_email2" autocomplete="email" value="<?php echo ( ! empty( $_POST['email2'] ) ) ? esc_attr( wp_unslash( $_POST['email2'] ) ) : ''; ?>" />
    </p>
    <?php
}

/**
 * Validate confirm email and terms input fields in the registration form in My Account page.
 *
 * @since 1.1.0
 * 
 * @param  WP_Error $error    	Error object.
 * @param  string $username 	Username.
 * @param  string $password 	Password.
 * @param  string $email    	Email address.
 * 
 * @return WP_Error 			Error object.
 */
function tlc_validation_registration_errors( $error, $username, $password, $email ) {
	$email2 = isset( $_POST['email2'] ) ? wp_unslash( $_POST['email2'] ) : '';
    
    if ( empty( $_POST['email2'] ) ) {
	$error->add( 'error', esc_html__( 'Please confirm your email address by re-typing your email address.', 'thelittlecraft' ) );
    }
    elseif ( strcmp( $email, $_POST['email2'] ) !== 0 ) {
	$error->add( 'error', esc_html__( 'The email addresses do not match.', 'thelittlecraft' ) );
    }
    elseif ( ! isset($_POST['terms']) || empty( $_POST['terms'] ) ) {
	$error->add( 'error', esc_html__( 'You must accept our Terms &amp; Conditions.', 'thelittlecraft' ) );
    }

    return $error;
}

/**
 * Disable auto-login after sucessful registration.
 * 
 * @param  boolean $true        Boolean true.
 * @param  inte $new_customer 	Customer ID.
 * 
 * @return boolean              Boolean false.
 */
function tlc_woocommerce_registration_disable_auto_login_new_customer( $true, $new_customer ) {
    return false;
}

/**
 * Force redirect to 'my account' page after registration.
 * 
 * @param  string $var 	Redirect link.
 * 
 * @return string      	Link to My Account page.
 */
function tlc_woocommerce_registration_redirect( $var ) {
	return wc_get_page_permalink( 'myaccount' );
}

/**
 * Display custom checkout fields.
 * 	- Hide Phone field
 * 	- Company name optinal field
 * 	- Display City and Postcode fields side by side
 * 	- Display Email field wide
 * 	- Add new Confiormation email field
 * 	
 * @param  array $fields 	Checkout fields.
 * 
 * @return array         	Custom chckout fields.
 */
function tlc_woocommerce_checkout_fields( $fields ) {
	
	// Hide Phone field - check tlc_woocommerce_billing_fields() to hide Phone from My Accoutn page as well
    unset( $fields['billing']['billing_phone'] );

    // Make Company name optional field
    $fields['billing']['billing_company']['required'] 	= false;
    $fields['shipping']['shipping_company']['required'] = false;
	
	// Make City and Postcode fields side by side
	$fields['billing']['billing_postcode']['class']   = array( 'form-row-first' );
	$fields['billing']['billing_city']['class'] 	  = array( 'form-row-last' );
	$fields['shipping']['shipping_postcode']['class'] = array( 'form-row-first' );
	$fields['shipping']['shipping_city']['class'] 	  = array( 'form-row-last' );
	
	// Make Email field wide
    $fields['billing']['billing_email']['class'] = array('form-row-wide');

    // Reorder Email field to be between Company name and Country
	$fields['billing']['billing_email']['priority'] = 35;
	
	// Add new Confirmation Email field
    $billing_email2 = array(
		'label'			=> __( 'Confirm Email Address', 'thelittlecraft' ),
		'required'		=> true,
		'type'			=> 'email',
		'class'			=> array( 'form-row-wide' ),
		'validate'		=> array( 'email' ),
		'autocomplete'	=> 'email',
		'priority'		=> 36, // Immediatly after Email field
		'placeholder'	=> _x( 'Retype Email Address', 'placeholder', 'thelittlecraft' )
    );

	$fields['billing']['billing_email-2'] = $billing_email2;
	
	return $fields;
}

/**
 * Validate that email and confirm email input match in the checkout form.
 */
function tlc_custom_checkout_field_process() {
	$email 	= isset( $_POST['billing_email'] ) ? wp_unslash( $_POST['billing_email'] ) : '';
	$email2 = isset( $_POST['billing_email-2'] ) ? wp_unslash( $_POST['billing_email-2'] ) : '';

    if ( ! empty( $email ) && ! empty( $email2 ) && strcmp( $email, $email2 ) !== 0 ) {
		wc_add_notice( __( 'The <strong>Email Address</strong> and <strong>Confirm Email Address</strong> do not match.', 'thelittlecraft' ), 'error' );
    }
}

/**
 * Reorder address fields page. Changes are displayed in My Account and Checkout pages.
 * 
 * @param  array $fields	Address fields.
 * 
 * @return array         	Reordered address fields.
 */
function tlc_woocommerce_default_address_fields_reorder( $fields ) {
	$fields['postcode']['priority'] = 65;
	$fields['city']['priority'] 	= 70;
	$fields['state']['priority'] 	= 45;
	
	return $fields;
}

/**
 * Remove Phone field also from edit billing address under My Account.
 * 
 * @param  array $fields 	Billing adress fields.
 * 
 * @return array         	Custom Billing address fields.
 */
function tlc_woocommerce_billing_fields( $fields ) {
    unset( $fields['billing_phone'] ); // check tlc_woocommerce_checkout_fields() to remove Phone from checkout as well
    return $fields;
}

/**
 * Display Required field note at the end of forms.
 * - Login and Register forms
 * - Edit account
 * - Edit billing address
 * - Edit shipping address
 * - Checkout
 * - Comments (and ratings)
 */
function tlc_display_required_field_note() {
    ?>
		<p><span class="required">*</span><em><?php _e( '&nbsp;Required field', 'thelittlecraft' ); ?></em></p>
    <?php
}

/**
 * Replace WooCommerce default PayPal icon.
 */
function tlc_paypal_checkout_icon() {
	return get_stylesheet_directory_uri() . '/images/paypal_credit_cards.png'; // write your own image URL here
}

/**
 * Remove WooCommerce SKUs Only on Product Pages.
 */
function tlc_remove_product_page_skus( $enabled ) {
    if ( ! is_admin() && is_product() ) {
        return false;
    }
    return $enabled;
}

/**
 * Set the strength requirement on the woocommerce password.
 *
 * Strength Settings
 * 4 = Very Strong
 * 3 = Strong (default)
 * 2 = Medium
 * 1 = Weak
 * 0 = Very Weak / Anything
 */
function tlc_woocommerce_min_strength_requirement( $strength ) {
    return 3;
}

/**
 * Remove the Storefront post hooks.
 *
 * @since 2.0.0
 */
function tlc_remove_storefront_post_meta_and_taxonomy() {
	remove_action( 'storefront_post_header_before', 'storefront_post_meta', 10 ); // It will be replaced by tlc_storefront_post_meta()
	remove_action( 'storefront_loop_post', 'storefront_post_taxonomy', 40 ); // It will be replaced by tlc_storefront_post_taxonomy()
	remove_action( 'storefront_single_post_bottom', 'storefront_post_taxonomy', 5 ); // It will be replaced by tlc_storefront_post_taxonomy()
}

/**
 * Display the post meta.
 *
 * @since 2.0.0
 */
function tlc_storefront_post_meta() {
	if ( 'post' !== get_post_type() ) {
		return;
	}

	// Posted on.
	$time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';

	if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) ) {
		$time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time><time class="updated" datetime="%3$s">%4$s</time>';
	}

	$time_string = sprintf(
		$time_string,
		esc_attr( get_the_date( 'c' ) ),
		esc_html( get_the_date() ),
		esc_attr( get_the_modified_date( 'c' ) ),
		esc_html( get_the_modified_date() )
	);

	$output_time_string = sprintf( '<a href="%1$s" rel="bookmark">%2$s</a>', esc_url( get_permalink() ), $time_string );

	$posted_on = '
		<span class="posted-on">' .
		/* translators: %s: post date */
		sprintf( __( 'Posted on %s', 'storefront' ), $output_time_string ) .
		'</span>';

	echo wp_kses(
		sprintf( '%1$s', $posted_on ), array(
			'span' => array(
				'class' => array(),
			),
			'a'    => array(
				'href'  => array(),
				'title' => array(),
				'rel'   => array(),
			),
			'time' => array(
				'datetime' => array(),
				'class'    => array(),
			)
		)
	);
}

/**
 * Display the post taxonomies.
 *
 * @since 2.0.0
 */
function tlc_storefront_post_taxonomy() {
	if ( 'post' !== get_post_type() ) {
		return;
	}

	// Author.
	$author = sprintf(
		'<a href="%1$s" class="url fn" rel="author">%2$s</a></span>',
		esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
		esc_html( get_the_author() )
	);

	// Categories.
	$categories_list = get_the_category_list( __( ', ', 'storefront' ) ); // translators: used between list items, there is a space after the comma

	// Tags.
	$tags_list = get_the_tag_list( '', __( ', ', 'storefront' ) ); // translators: used between list items, there is a space after the comma
	
	// Comments.
	$comments = '';

	if ( ! post_password_required() && ( comments_open() || 0 !== intval( get_comments_number() ) ) ) {
		$comments_number = get_comments_number_text( __( 'Leave a comment', 'storefront' ), __( '1 Comment', 'storefront' ), __( '% Comments', 'storefront' ) );

		$comments = sprintf(
			'<a href="%1$s">%2$s</a></span>',
			esc_url( get_comments_link() ),
			$comments_number
		);
	}
	?>

	<aside class="entry-taxonomy">
		<div class="vcard author">
			<?php echo get_avatar( get_post(), 128 ); ?>
		</div>

		<div class="post-author">
			<div class="label">
				<?php echo esc_html( __( 'Posted by', 'thelittlecraft' ) ); ?>
			</div>
			<?php echo wp_kses_post( $author ); ?>
		</div>

		<?php if ( $categories_list ) : ?>
		<div class="cat-links">
			<div class="label">
				<?php echo esc_html( _n( 'Category:', 'Categories:', count( get_the_category() ), 'storefront' ) ); ?>
			</div>
			<?php echo wp_kses_post( $categories_list ); ?>
		</div>
		<?php endif; ?>

		<?php if ( $tags_list ) : ?>
		<div class="tags-links">
			<div class="label">
				<?php echo esc_html( _n( 'Tag:', 'Tags:', count( get_the_tags() ), 'storefront' ) ); ?>
			</div>
			<?php echo wp_kses_post( $tags_list ); ?>
		</div>
		<?php endif; ?>

		<div class="post-comments">
			<div class="label">
				<?php echo esc_html( __( 'Comments', 'thelittlecraft' ) ); ?>
			</div>
			<?php echo wp_kses_post( $comments ); ?>
	</aside>

	<?php
}

/**
 * Adds socia meta tag to header.
 */
function tlc_social_meta() {
    echo '<meta name="p:domain_verify" content="99280a9063b89f7dd9675262c476da83"/>'; // Pinterest domain verification
    echo '<meta property="fb:admins" content="100011416258345" />'; // Facebook admin data
}

/**
 *  Hide shipping class methods when free shipping is available.
 *
 * @param array $rates Array of rates found for the package
 * @param array $package The package array/object being shipped
 * 
 * @return array of modified rates 
 */
function tlc_hide_shipping_when_free_is_available( $rates, $package ) {
    $free = array();
	foreach ( $rates as $rate_id => $rate ) {
		if ( 'free_shipping' === $rate->method_id ) {
			$free[ $rate_id ] = $rate;
			break;
		}
	}
	return ! empty( $free ) ? $free : $rates;
}

/**
 * Apply CSS to the Admin page.
 */
function tlc_css_admin() {
	$styles = '<style>';
	$styles .= '.wp-core-ui select[name="wpo_wcpdf_send_emails"] {';
	$styles .= 'margin-bottom: 5px; }';

	echo $styles;
}