(function($) {

	var container;

	$(document).ready(function() {

		container = $('#artwork_artist_box');

		if(!box_artist_settings.isAdmin)
			container.find('.chosen').chosen({
				allow_single_deselect: true
			});

	});

})(jQuery);