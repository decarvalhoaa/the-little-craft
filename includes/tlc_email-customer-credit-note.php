<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'TLC_WC_Email_Customer_Credit_Note' ) ) :

/**
 * Overrides the Email Customer Credit Note class
 *
 * The Customer Credit Note email is sent to the customer via admin on the order locale
 *
 * @class 		TLC_WC_Email_Customer_Credit_Note
 * @author 		Antonio de Carvalho
 * @extends 		WC_Email_Customer_Credit_Note
 */
class TLC_WC_Email_Customer_Credit_Note extends WC_Email_Customer_Credit_Note {

	/**
	 * trigger function.
	 *
	 * @access public
	 * @return void
	 */
	function trigger( $order ) {
		if ( ! is_object( $order ) ) {
			$order = wc_get_order( absint( $order ) );
		}

		$this->switch_language( $order->id );
		$this->set_email_strings();

		// Call parent trigger
		parent::trigger( $order );
	}

	/**
	 * Polylang compatibility helper function: set language before pdf creation
	 */
	function switch_language( $order_id ) {
		if ( class_exists( 'Polylang' ) ) {
			global $locale, $wp_locale, $polylang, $woocommerce;

			$order_language = pll_get_post_language( $order_id, 'locale' );
			if ( $order_language == '' ) {
				$order_language = pll_default_language( 'locale' );
			}
			$this->order_language = $order_language;
			$this->previous_language = pll_current_language( 'locale' );

			// unload plugin's textdomains
			unload_textdomain( 'default' );
			unload_textdomain( 'woocommerce' );

			// set locale to order locale
			$locale = apply_filters( 'locale', $this->order_language );
			$polylang->curlang->locale = $this->order_language;
			$mo = new PLL_MO();
			$mo->import_from_db( $GLOBALS['polylang']->model->get_language( $this->order_language ) );
			$GLOBALS['l10n']['pll_string'] = &$mo;

			// (re-)load plugin's textdomain in order locale
			load_default_textdomain( $this->order_language );
			$woocommerce->load_plugin_textdomain();
			$wp_locale = new WP_Locale();
		}
	}

	/**
	 * Set email strings.
	 */
	function set_email_strings() {
		$this->subject        = __( 'Credit Note for order {order_number} from {order_date}', 'wpo_wcpdf_pro' );
		$this->heading        = __( 'Credit Note for order {order_number}', 'wpo_wcpdf_pro' );
		$this->body           = __( 'A refund has been issued for your order, attached to this email you will find a credit note with the details.', 'wpo_wcpdf_pro' );
	}

}

endif;

return new TLC_WC_Email_Customer_Credit_Note();
