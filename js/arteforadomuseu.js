(function($) {

	$(document).ready(function() {

		$(window).scroll(function() {
			if($(window).scrollTop() >= 10) {
				$('#masthead').addClass('scrolled');
			} else {
				$('#masthead').removeClass('scrolled');
			}
		});

	});

})(jQuery);