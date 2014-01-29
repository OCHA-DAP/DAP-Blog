/*
 *
 * RADIUM_Options_radio_img function
 * Changes the radio select option, and changes class on images
 *
 */
function radium_radio_img_select(relid, labelclass){
	jQuery(this).prev('input[type="radio"]').prop('checked');

	jQuery('.radium-radio-img-'+labelclass).removeClass('radium-radio-img-selected');	
	
	jQuery('label[for="'+relid+'"]').addClass('radium-radio-img-selected');
}//function