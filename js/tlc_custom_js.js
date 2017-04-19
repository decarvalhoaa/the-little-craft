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
	//var pll_lang = $.cookie( 'pll_language' ); // WC3.x change from jQuery Cookie to Vanilla JS cookie https://github.com/js-cookie/js-cookie
	var pll_lang = Cookies.get( 'pll_language' );
	if ( pll_lang === null || pll_lang === undefined || pll_lang === '' || pll_lang == 'en' ) {
		$( '#opt_email' ).attr( "placeholder", "Your Email address" );
	} else {
		$( '#opt_email' ).attr( "placeholder", "Deine E-Mail Adresse" );
	}

	function resetAloEasyEmailWidgetFeedback() {
		//e.preventDefault(); // prevent the default action
		//e.stopPropagation(); // stop the click from bubbling
		$( '#alo_easymail_widget_feedback' ).removeClass( 'alo_easymail_widget_ok' ).removeClass( 'alo_easymail_widget_error' );
	}

	// Reset class of the #alo_easymail_widget_feedback container when the submit button of the form is triggered
	$( '#alo_easymail_widget_form input[type=submit]').click( resetAloEasyEmailWidgetFeedback );
	$( '#alo_easymail_widget_form input[type=radio]').change( resetAloEasyEmailWidgetFeedback );


	/**
	 * Enable prettyPhoto Lightboxes for the images in blog posts
	 */
	$( '.blog .content-area a, .single-post .content-area a' ).has( 'img' ).each( function() {
		$( this ).attr( 'data-rel', 'prettyPhoto[photo-gallery]' );
	});

} );
