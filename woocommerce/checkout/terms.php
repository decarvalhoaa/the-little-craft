<?php
/**
 * Checkout terms and conditions checkbox
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.5.0
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( wc_get_page_id( 'terms' ) > 0 && apply_filters( 'woocommerce_checkout_show_terms', true ) ) : ?>
	<?php do_action( 'woocommerce_checkout_before_terms_and_conditions' ); ?>
    <p class="form-row terms wc-terms-and-conditions">
	<input type="checkbox" class="input-checkbox" name="terms" <?php checked( apply_filters( 'woocommerce_terms_is_checked_default', isset( $_POST['terms'] ) ), true ); ?> id="terms" />
        <label for="terms" class="checkbox"><?php printf( __( 'I&rsquo;ve read and accept the <a href="%s" target="_blank">Terms &amp; Conditions</a> and <a href="%s" target="_blank">Privacy Policy</a>', 'thelittlecraft' ), esc_url( wc_get_page_permalink( 'terms' ) ), esc_url( tlc_pll_get_permalink( 210 ) ) ); ?> <span class="required">*</span></label>
        <input type="hidden" name="terms-field" value="1" />
    </p>
<?php endif; ?>
