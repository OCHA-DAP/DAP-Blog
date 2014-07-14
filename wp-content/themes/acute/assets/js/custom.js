// IF JS IS ENABLED, REMOVE 'no-js' AND ADD 'js' CLASS
jQuery('html').removeClass('no-js').addClass('js');

jQuery(document).ready(function($) {
	//FITVIDS
	$("body").fitVids();
	 
	//AUTOHEIGHT TEXTAREA
	jQuery('textarea.auto-height').flexText();
	
	//FORM VALIDATION
	if (jQuery().validate) { jQuery("#commentform").validate(); }
	 
   	//DROPDOWNS - SUPERFISH
   	$('#primary-nav > ul')
   		.superfish({
   			delay: 200,
   			animation: {opacity:'show', height:'show'},
   			speed: 'fast',
   			disableHI: true,
   			cssArrows: false,
   	});
	
	//RESPONSIVE MENU
	$('header nav').meanmenu();
	
	//IPAD PANEL TRIGGER TOUCH
		//CLICK EVENTS
		var ua = navigator.userAgent,
	    	clickevent = (ua.match(/iPad/i) || ua.match(/iPhone/i) || ua.match(/Android/i)) ? "touchstart" : "click";
	   	    console.log(clickevent);
	   	
		//TOGGLES
		$(document).on(clickevent, '.toggle', function(event){
		event.preventDefault();
		if ($('#panel').hasClass('panel-open')) {
		  closeMenu();
		} else {
		  openMenu();
		}
		}); 
	
		//OPEN
		function openMenu(){
		 	$('#panel').addClass('panel-open');
		}
		
		//CLOSE 
		function closeMenu(){
			$('#panel').removeClass('panel-open');
		}
	
		//UI TO TOP
		jQuery().UItoTop({ 
			text: '',
			easingType: 'easeOutQuart'
		});
	
		//ISOTOPE MAIN
		var $container = $('#isotope-container');
			$container.imagesLoaded( function(){
			$container.isotope({
			 itemSelector: '.isotope-item',
			 layoutMode : 'fitRows',
		 	 resizesContainer: true
		});

		//ISOTOPE FILTER
	    $(function(){
	        var $container = $('#isotope-container');
	            optionFilter = jQuery('#filter'),
	            optionFilterLinks = optionFilter.find('a');
	        
	        optionFilterLinks.attr('href', '#');
	        
	        optionFilterLinks.click(function(){
	            var selector = jQuery(this).attr('data-filter');
	           $container.imagesLoaded( function(){ 
	            $container.isotope({ 
	                filter : '.' + selector, 
	                itemSelector : '.isotope-item',
	                resizesContainer: true,
	            });
	            	});
	            optionFilterLinks.removeClass('active');
	            jQuery(this).addClass('active');
	            return false;
	        });
	    });
			  
		});

});

/*=================================*/
/* ADD CLASS TO EACH CHARACTER - FOOTER COUNTER
/*=================================*/    
(function ($) {
    $.fn.CharIterator = function (options) {
        var defaults = {
            skip_spaces: true,
            before: '<span class="count">',
            after: '</span>'
        };

        var opts = $.extend({}, defaults, options);

        var trim = function (text) {
            return text.replace(/^\s+/g, '').replace(/\s+$/g, '');
        };

        return this.each(function () {
            var $this = $(this);

            var chars = jQuery.map($this.text().split(''), function (c) {
                if (trim(c) != '' || !opts.skip_spaces) {
                    return opts.before + c + opts.after;
                }
                return c;
            });
            $this.html(chars.join(''));
        });
    };
})(jQuery);

(function ($) {
    $('span.numbers').CharIterator({});
})(jQuery);


/* ---------------------------------------------------------------------- */
/* BEAN ANNOUNCEMENT BAR - THEME OPTIONS 
/* ---------------------------------------------------------------------- */
jQuery(document).ready(function($) {
	if( $.cookie('bean_announcement_cookie') == null && $("#bean_announcement").hasClass('load-announcement') ) {
		$("#bean_announcement").css({'top' : '0px'});
		$("#header").css({paddingTop : '105px'});
		$("#bean_announcement .close-btn").addClass('open');
		$("#bean_announcement .close-btn").removeClass('closed');
		$("#bean_announcement .close-btn").css("display", "block");
		$("#bean_announcement .openbtn").css("display", "none");
		$("#panel .toggle").addClass('announcement');
	}; 
	
	if($.cookie('bean_announcement_cookie') == 'closed') {
		$("#bean_announcement").css({'top' : '-70px'});
		$("#header").css({paddingTop : '40px'});
		$("#bean_announcement .close-btn").addClass('closed');
		$("#bean_announcement .close-btn").removeClass('open');
		$("#bean_announcement .close-btn").css("display", "none");
	};
	
	if($.cookie('bean_announcement_cookie') == 'open') {
		$("#bean_announcement").css({'top' : '0px'});
		$("#header").css({paddingTop : '70px'});
		$("#bean_announcement .close-btn").addClass('open');
		$("#bean_announcement .close-btn").removeClass('closed');
		$("#bean_announcement .close-btn").css("display", "block");
	};
	
	$("#bean_announcement .close-btn").click(function(){
		$("#bean_announcement").animate({'top' : '-70px'}, 200);
		$("#header").animate({paddingTop : '40px'}, 200);
		$("#panel div.toggle.announcement").animate({'top' : '42px'}, 200);
		$("#bean_announcement .close-btn").addClass('closed');
		$("#bean_announcement .close-btn").removeClass('open');
		$(this).css("display", "none");
		$.cookie('bean_announcement_cookie', 'closed', { expires: 1, path: '/' });
	});	

});