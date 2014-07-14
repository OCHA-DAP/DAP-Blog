//http://goo.gl/fc9GP

jQuery( document ).ready( function($) {

	var	$linkSettings  = $('#radium-meta-box-link').hide(),
		$videoSettings = $('#radium-meta-box-video').hide(),
		$audioSettings = $('#radium-meta-box-audio').hide(),
		$postFormat    = $('#post-formats-select input[name="post_format"]');
	
	$postFormat.each(function() {
		
		var $this = $(this);

		if( $this.is(':checked') )
			changePostFormat( $this.val() );

	});

	$postFormat.change(function() {

		changePostFormat( $(this).val() );

	});

	function changePostFormat( val ) {

		$linkSettings.hide();
		$videoSettings.hide();
		$audioSettings.hide();

		if( val === 'link' ) {

			$linkSettings.show();

		} else if( val === 'video' ) {

			$videoSettings.show();
			
		} else if( val === 'audio' ) {

			$audioSettings.show();
			
		}
	}
}); 