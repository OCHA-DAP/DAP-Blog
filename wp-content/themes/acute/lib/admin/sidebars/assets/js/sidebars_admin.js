jQuery( document ).ready( function () {
	jQuery( '.radium-conditions.tabs' ).tabs();
	jQuery( '.radium-conditions.tabs .inner-tabs' ).tabs();

	jQuery( '#radium-conditions .advanced-settings a' ).click( function ( e ) {
		jQuery( this ).parent( 'li' ).siblings( '.advanced' ).toggleClass( 'hide' );

		var new_status = '1'; // Do display.
		if ( jQuery( this ).parent( 'li' ).siblings( '.advanced' ).hasClass( 'hide' ) ) {
			new_status = '0'; // Don't display.
		}

		// Perform the AJAX call.	
		jQuery.post(
			ajaxurl, 
			{ 
				action : 'radiumsidebars-toggle-advanced-items', 
				radiumsidebars_advanced_noonce : radiumsidebars_localized_data.radiumsidebars_advanced_noonce, 
				new_status: new_status
			},
			function( response ) {}	
		);

		return false;
	});
});