
// start the popup specific scripts
// safe to use $
jQuery(document).ready(function($) {
    var beans = {
    
    	loadVals: function(){
    		var shortcode = $('#_bean_shortcode').text(),
    			uShortcode = shortcode;
    		
    		// fill in the gaps eg {{param}}
    		$('.bean-input').each(function() {
    			var input = $(this),
    				id = input.attr('id'),
    				id = id.replace('bean_', ''),		// gets rid of the bean_ prefix
    				re = new RegExp("{{"+id+"}}","g");
    				
    			uShortcode = uShortcode.replace(re, input.val());
    		});
    		
    		// adds the filled-in shortcode as hidden input
    		$('#_bean_ushortcode').remove();
    		$('#bean-sc-form-table').prepend('<div id="_bean_ushortcode" class="hidden">' + uShortcode + '</div>');
    	},
    	
    	cLoadVals: function(){
    		var shortcode = $('#_bean_cshortcode').text(),
    			pShortcode = '';
    			shortcodes = '';
    		
    		// fill in the gaps eg {{param}}
    		$('.child-clone-row').each(function() {
    			var row = $(this),
    				rShortcode = shortcode;
    			
    			$('.bean-cinput', this).each(function() {
    				var input = $(this),
    					id = input.attr('id'),
    					id = id.replace('bean_', '')		// gets rid of the bean_ prefix
    					re = new RegExp("{{"+id+"}}","g");
    					
    				rShortcode = rShortcode.replace(re, input.val());
    			});
    	
    			shortcodes = shortcodes + rShortcode + "\n";
    		});
    		
    		// adds the filled-in shortcode as hidden input
    		$('#_bean_cshortcodes').remove();
    		$('.child-clone-rows').prepend('<div id="_bean_cshortcodes" class="hidden">' + shortcodes + '</div>');
    		
    		// add to parent shortcode
    		this.loadVals();
    		pShortcode = $('#_bean_ushortcode').text().replace('{{child_shortcode}}', shortcodes);
    		
    		// add updated parent shortcode
    		$('#_bean_ushortcode').remove();
    		$('#bean-sc-form-table').prepend('<div id="_bean_ushortcode" class="hidden">' + pShortcode + '</div>');
    	},
    	
    	children: function() {
    		// assign the cloning plugin
    		$('.child-clone-rows').appendo({
    			subSelect: '> div.child-clone-row:last-child',
    			allowDelete: false,
    			focusFirst: false
    		});
    		
    		// remove button
    		$('.child-clone-row-remove').live('click', function() {
    			var	btn = $(this),
    				row = btn.parent();
    			
    			if( $('.child-clone-row').size() > 1 )
    			{
    				row.remove();
    			}
    			else
    			{
    				alert('You need a minimum of one row');
    			}
    			
    			return false;
    		});
    		
    		// assign jUI sortable
    		$( ".child-clone-rows" ).sortable({
				placeholder: "sortable-placeholder",
				items: '.child-clone-row'
				
			});
    	},
    	
    	resizeTB: function(){
			var	ajaxCont = $('#TB_ajaxContent'),
				tbWindow = $('#TB_window'),
				beanPopup = $('#bean-popup');

            tbWindow.css({
                height: beanPopup.outerHeight() + 50,
                width: beanPopup.outerWidth(),
                marginLeft: -(beanPopup.outerWidth()/2)
            });

			ajaxCont.css({
				paddingTop: 0,
				paddingLeft: 0,
				paddingRight: 0,
				height: (tbWindow.outerHeight()-47),
				overflow: 'auto', // IMPORTANT
				width: beanPopup.outerWidth()
			});
			
			$('#bean-popup').addClass('no_preview');
    	},
    	
    	load: function(){
    		var	beans = this,
    			popup = $('#bean-popup'),
    			form = $('#bean-sc-form', popup),
    			shortcode = $('#_bean_shortcode', form).text(),
    			popupType = $('#_bean_popup', form).text(),
    			uShortcode = '';
    		
    		// resize TB
    		beans.resizeTB();
    		$(window).resize(function() { beans.resizeTB() });
    		
    		// initialise
    		beans.loadVals();
    		beans.children();
    		beans.cLoadVals();
    		
    		// update on children value change
    		$('.bean-cinput', form).live('change', function() {
    			beans.cLoadVals();
    		});
    		
    		// update on value change
    		$('.bean-input', form).change(function() {
    			beans.loadVals();
    		});
    		
    		// when insert is clicked
    		$('.bean-insert', form).click(function() {    		 			
    			if(window.tinyMCE)
				{
					window.tinyMCE.execInstanceCommand('content', 'mceInsertContent', false, $('#_bean_ushortcode', form).html());
					tb_remove();
				}
    		});
    	}
	}
    
    // run
    $('#bean-popup').livequery( function() { beans.load(); } );
});