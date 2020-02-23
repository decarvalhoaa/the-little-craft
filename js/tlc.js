jQuery( document ).ready( function( $ ) {
	/**
	 * Handle the erro highligh of the Confirm Email Address field during checkout
	 */
	function toggleConfirmEmailErrorClass() {
		if ( $( '#billing_email' ).length && $( '#billing_email-2' ).val().toLowerCase() !== $( '#billing_email' ).val().toLowerCase() ) {
			$( '#billing_email-2_field' ).addClass( 'tlc-woocommerce-invalid-required-field' );
		}
		else if ( $( '#billing_email' ).length && $( '#billing_email-2' ).val().toLowerCase() === $( '#billing_email' ).val().toLowerCase() ) {
			$( '#billing_email-2_field' ).removeClass( 'tlc-woocommerce-invalid-required-field' );
		}
	}
	$( '#billing_email-2' ).blur( toggleConfirmEmailErrorClass );
	//$( '#place_order' ).click( toggleConfirmEmailErrorClass );
} );