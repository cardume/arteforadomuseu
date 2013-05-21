(function($) {

	/*
	 * Artwork interactions
	 */

	var box = afdmLightbox({containerID: 'add_artwork'});

	box.opened(function() {
		box.find('textarea').autosize();
		if(!box.streetview)
			box.streetview = new streetviewBox({geocoder: geocodeBox()});
	});

	box.closed(function() {
		box.clearFormInputs();
		if(box.streetview) {
			box.streetview.geocoder.clearMarkers();
			box.streetview.geocoder.clearResults();
		}
	});

	$(document).ready(function() {
		$('.add_artwork').click(function() {
			box.open();
			return false;
		});

		box.find('form').ajaxForm({
			beforeSend: initProgressBar,
			uploadProgress: updateProgressBar,
			success: success,
			data: { action: 'submit_artwork' },
			url: artworks.ajaxurl,
			dataType: 'json',
			type: 'POST'
		});

		var progressBar;

		function initProgressBar() {
			box.animateToTop();
			progressBar = $('<div class="progress-bar"><div class="progress"></div><span class="percentage">0%</span></div>');
			box.setMessage(progressBar);
		}

		function updateProgressBar(event, position, total, percentComplete) {
			var percentVal = percentComplete + '%';
			var text = percentVal;
			progressBar.find('.progress').width(percentVal);
			if(percentVal == '100%')
				text = artworks.crunching_message;
			progressBar.find('.percentage').text(text);
			box.setMessage(progressBar);
		}

		function success(data) {

			if(data.error_msg)
				box.setMessage(data.error_msg, 'error');
			else
				box.setMessage(data.success_msg, 'success');

		}

	});

})(jQuery);