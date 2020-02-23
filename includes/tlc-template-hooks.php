<?php
/**
 * The Little Craft hooks
 *
 */

/**
 * General
 *
 * @see tlc_small_business_vat_notice_footer()
 * @see tlc_small_business_vat_notice_price_link_fix()
 * @see tlc_display_product_price_vat_notice()
 * @see tlc_set_storefront_setting_values()
 * @see tlc_woocommerce_default_address_fields_reorder()
 * @see tlc_display_required_field_note()
 * @see tlc_social_meta()
 */
add_action( 'storefront_before_footer', 'tlc_small_business_vat_notice_footer', 50 ); // Display the small business VAT notice before footer in all pages
add_action( 'init', 'tlc_small_business_vat_notice_price_link_fix' ); // Remove product price, in the shop loop, from linking to the product overview page. Added after the product title
add_filter( 'woocommerce_get_price_html', 'tlc_display_product_price_vat_notice', 10, 2 ); //Adds VAT notice to product price on single product overview page and product listings
add_filter( 'storefront_setting_default_values', 'tlc_set_storefront_setting_values', 20, 1 ); // Sets custom theme colours
add_filter( 'woocommerce_default_address_fields', 'tlc_woocommerce_default_address_fields_reorder', 10, 1 ); // Reorder address fields page. Changes are displayed in My Account and Checkout pages
add_action( 'woocommerce_after_customer_login_form', 'tlc_display_required_field_note' ); // Display Required field note at the end of forms
add_action( 'woocommerce_edit_account_form_end', 'tlc_display_required_field_note' ); // Display Required field note at the end of forms
add_action( 'woocommerce_after_edit_account_address_form', 'tlc_display_required_field_note' ); // Display Required field note at the end of forms
add_action( 'woocommerce_after_checkout_form', 'tlc_display_required_field_note' ); // Display Required field note at the end of forms
add_action( 'comment_form', 'tlc_display_required_field_note' ); // Display Required field note at the end of forms
add_action( 'wp_head','tlc_social_meta' ); // Adds socia meta tag to header

/**
 * My Account - Login & Registration
 *
 * @see tlc_register_form_terms()
 * @see tlc_woocommerce_register_form_email2()
 * @see tlc_validation_registration_errors()
 * @see tlc_woocommerce_registration_disable_auto_login_new_customer()
 * @see tlc_woocommerce_billing_fields()
 * @see tlc_woocommerce_min_strength_requirement()
 */
add_action( 'woocommerce_register_form', 'tlc_register_form_terms', 50 ); // Display terms checkbox in the registration form in My Account page
add_action( 'woocommerce_register_form', 'tlc_woocommerce_register_form_email2' ); // Display confirm email input field in the registration form in My Account page
add_filter( 'woocommerce_process_registration_errors', 'tlc_validation_registration_errors', 10, 4 ); // Validate confirm email and terms input fields in the registration form in My Account page
//add_filter('woocommerce_registration_auth_new_customer', 'tlc_woocommerce_registration_disable_auto_login_new_customer', 10, 2 ); // Disable auto-login after sucessful registration
//add_filter( 'woocommerce_registration_redirect', 'tlc_woocommerce_registration_redirect', 10, 1 ); // Force redirect to 'my account' page after registration
add_filter( 'woocommerce_billing_fields' , 'tlc_woocommerce_billing_fields', 10 , 1 ); //Remove Phone field also from edit billing address under My Account
add_filter( 'woocommerce_min_password_strength', 'tlc_woocommerce_min_strength_requirement' ); // Set the strength requirement on the woocommerce password

/**
 * Cart & Checkout
 *
 * @see tlc_woocommerce_checkout_fields()
 * @see tlc_custom_checkout_field_process()
 * @see tlc_paypal_checkout_icon()
 * @see tlc_hide_shipping_when_free_is_available()
 */
add_filter( 'woocommerce_checkout_fields', 'tlc_woocommerce_checkout_fields', 10, 1 );// Display custom checkout fields
add_action( 'woocommerce_checkout_process', 'tlc_custom_checkout_field_process', 10 ); // Validate that email and confirm email input match in the checkout form
add_filter( 'woocommerce_paypal_icon', 'tlc_paypal_checkout_icon' ); // Replace WooCommerce default PayPal icon
add_filter( 'woocommerce_package_rates', 'tlc_hide_shipping_when_free_is_available', 10, 2 ); // Hide shipping class options when free shipping is available

/**
 * Product
 *
 * @see tlc_remove_product_page_skus()
 */
//add_filter( 'wc_product_sku_enabled', 'tlc_remove_product_page_skus' ); // Remove WooCommerce SKUs Only on Product Pages

/**
 * Post
 *
 * @see tlc_remove_storefront_post_meta_and_taxonomy()
 * @see tlc_storefront_post_meta()
 */
add_action( 'init', 'tlc_remove_storefront_post_meta_and_taxonomy', 10 ); // Remove the Storefront post hooks
add_action( 'storefront_post_header_before', 'tlc_storefront_post_meta', 10 ); // Display the post meta
add_action( 'storefront_loop_post', 'tlc_storefront_post_taxonomy', 40 ); // Display the post taxonomies in the loop post
add_action( 'storefront_single_post_bottom', 'tlc_storefront_post_taxonomy', 5 ); // Display the post taxonomies in the single product page

/**
 * Footer
 * 
 * @see tlc_footer_credit()
 */
add_action( 'init', 'tlc_footer_credit', 10 ); // Replaces Storefront footer credit with The Little Craft custom credit

/**
 * Admin
 * 
 * @see tlc_css_admin()
 */
add_action( 'admin_head', 'tlc_css_admin' ); // Apply CSS to the Admin page