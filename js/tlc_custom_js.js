jQuery( document ).ready( function( $ ) {

	// Handle the erro highligh of the Confirm Email Address field during checkout
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


	/**
	 * ALO EasyMail Newsletter
	 */
	// Add placeholder text to Alo EasyEmail Newsletter form
	$( '#opt_email' ).attr( "placeholder", "Your E-Mail address" );

	function resetAloEasyEmailWidgetFeedback( e ) {
		//e.preventDefault(); // prevent the default action
		//e.stopPropagation(); // stop the click from bubbling
		$( '#alo_easymail_widget_feedback' ).removeClass( 'alo_easymail_widget_ok' ).removeClass( 'alo_easymail_widget_error' );
	}

	// Reset class of the #alo_easymail_widget_feedback container when the submit button of the form is triggered
	$( '#alo_easymail_widget_form input[type=submit]').click( resetAloEasyEmailWidgetFeedback );
	$( '#alo_easymail_widget_form input[type=radio]').change( resetAloEasyEmailWidgetFeedback );



	/**
	* If the javascript vars are defined, then it's a single product page and the widget
	* 'woocommercer_recently_viewed_products' is active. In this case, this code will run
	* and updates the cookie that holds the list of recently viewed products ids, in the
	* event the user is served a cached paged. The cached page could have been generated
	* during other user's visit, and therefore containing his/hers list of recently viewed
	* product ids. Without this code snippet, the cookie would not be updated.
	* The code also inserts a hidden placeholder for the widget HTML, if not present.
	* The placeholder will be replaced by the refreshed fragment return via ajax (see
	* function tlc_get_additional_refreshed_fragments() for details). This avoid, that
	* no widget is printed, if the cached page was generated upon the first product view.
	*/
	if ( typeof tlc_widget_id !== 'undefined' && typeof tlc_product_id !== 'undefined' && typeof tlc_wc_recently_viewed_products_cookie !== 'undefined' ) {
		if ( $( '#' + tlc_widget_id ).length == 0 ) {
		    $( '.widget-area' ).append( '<aside id="' + tlc_widget_id + '" class="hidden"></aside>' );
		}

		if ( ! $.cookie( tlc_wc_recently_viewed_products_cookie.name ) ) {
		    viewed_products = [];
		}
		else {
		    viewed_products = $.cookie( tlc_wc_recently_viewed_products_cookie.name ).split( '|' );
		}

		if ( viewed_products.indexOf( tlc_product_id ) >= 0 ) {
		    viewed_products.splice( viewed_products.indexOf( tlc_product_id ), 1 );
		}
		viewed_products.push( tlc_product_id );

		if ( viewed_products.length > 15 ) {
		    viewed_products.shift();
		}

		$.cookie( tlc_wc_recently_viewed_products_cookie.name, viewed_products.join( '|' ), {
		    expires: '',
		    path: tlc_wc_recently_viewed_products_cookie.path,
		    domain: tlc_wc_recently_viewed_products_cookie.domain,
		    secure: false
		} )
	}
} );
