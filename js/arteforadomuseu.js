(function($) {

	$(document).ready(function() {

		var navigation = responsiveNav('#main-nav', {
			label: 'menu'
		});

		$(window).scroll(function() {
			if($(window).scrollTop() >= 10) {
				$('#masthead').addClass('scrolled');
			} else {
				$('#masthead').removeClass('scrolled');
			}
		});

	});


	/*
	 * Subsection
	 */

	var subsection = {
		
	}

})(jQuery);