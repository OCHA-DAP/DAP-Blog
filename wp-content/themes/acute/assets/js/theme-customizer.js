/**
 * This file adds some LIVE to the Theme Customizer live preview. To leverage
 * this, set your custom settings to 'postMessage' and then add your handling
 * here. Your javascript should grab settings from customizer controls, and 
 * then make any necessary changes to the page using jQuery.
 */
( function( $ ) {

	// Update the site title in real time...
	wp.customize( 'blogname', function( value ) {
		value.bind( function( newval ) {
			$( '#logo_text' ).html( newval );
		} );
	} );
		
	//Update the site description in real time...
	wp.customize( 'header_tagline', function( value ) {
		value.bind( function( newval ) {
			$( '.header-tagline' ).html( newval );
		} );
	} );
	
	//Update the site description in real time...
	wp.customize( 'footer_copyright', function( value ) {
		value.bind( function( newval ) {
			$( '.copyright' ).html( newval );
		} );
	} );
	
} )( jQuery );
