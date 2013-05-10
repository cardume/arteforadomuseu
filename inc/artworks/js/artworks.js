(function($) {

	/*
	 * Artwork interactions
	 */

	var box = afdmLightbox({containerID: 'add_artwork'});

	$(document).ready(function() {
		$('.add_artwork').click(function() {
			box.open();
			return false;
		});

		box.find('form').submit(function() {
			add($(this).serialize());
			return false;
		});
	});

	box.opened(function() {
		box.find('textarea').autosize();
		if(!box.streetview)
			box.streetview = streetviewBox({geocoder: geocodeBox()});
	});

	box.closed(function() {
		box.clearFormInputs();
		if(box.streetview) {
			box.streetview.geocoder.clearMarkers();
			box.streetview.geocoder.clearResults();
		}
	});

	function add(serializedData) {
		box.animateToTop();
		box.setMessage(artworks.sending_msg);
		$.post(artworks.ajaxurl + '?' + serializedData, {action: 'submit_artwork'}, function(data) {
			if(data.error_msg)
				box.setMessage(data.error_msg, 'error');
			else
				box.setMessage(data.success_msg, 'success');
		}, 'json');
	}

})(jQuery);