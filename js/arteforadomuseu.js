(function($) {

	$(document).ready(function() {

		var navigation = responsiveNav('#main-nav');

		$(window).scroll(function() {
			if($(window).scrollTop() >= 10) {
				$('#masthead').addClass('scrolled');
			} else {
				$('#masthead').removeClass('scrolled');
			}
		});

	});

})(jQuery);