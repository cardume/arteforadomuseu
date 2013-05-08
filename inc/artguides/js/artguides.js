(function($) {

	/*
	 * Artwork <-> Artguide interactions
	 */

	var artworkBox = afdmLightbox({containerID: 'add_artwork_to_guide'});

	var	artwork,
		artworkTitle;

	$(document).ready(function() {

		$('.add_artwork_to_guide').click(function() {
			artwork = $(this).data('artwork');
			artworkTitle = $(this).data('artwork-title');
			artworkBox.open();
			return false;
		});

		artworkBox.find('form').submit(function() {
			addArtwork($(this).serialize());
			return false;
		});

	});

	artworkBox.opened(function() {
		artworkBox.find('h2 .title').text(artworkTitle);
		artworkBox.find('input.artwork_id').val(artwork);
		artworkBox.find('textarea').autosize();
	});

	artworkBox.closed(function() {
		artworkBox.find('h2 .title, .error-message').text('');
		artworkBox.clearFormInputs();
	});

	function addArtwork(serializedData) {
		artworkBox.animateToTop();
		artworkBox.setMessage(artguides.sending_msg);
		$.post(artguides.ajaxurl + '?' + serializedData, {action: 'add_artwork_to_guide'}, function(data) {
			if(data.error_msg) {
				artworkBox.setMessage(data.error_msg, 'error');
			} else {
				artworkBox.setMessage(data.success_msg, 'success');
			}
		}, 'json');

	}

	/*
	 * Delete/remove UI stuff
	 */

	$(document).ready(function() {
		$('form#remove_artwork').submit(function() {
			removeArtwork($(this).serialize(), $(this).parents('li'));
			return false;
		});
	});

	function removeArtwork(serializedData, $el) {
		var r = confirm(artguides.confirm_delete);
		if(!r)
			return false;
		$.post(artguides.ajaxurl + '?' + serializedData, {action: 'remove_artwork_from_guide'}, function(data) {
			if(data.error_msg) {
				alert(data.error_msg);
			} else {
				$el.fadeOut('fast', function() { $(this).remove(); });
			}
		}, 'json');
	}

	/*
	 * Art guide interactions
	 */

	var guideBox = afdmLightbox({containerID: 'add_guide'});

	$(document).ready(function() {

		$('.add_guide').click(function() {
			guideBox.open();
			return false;
		});

		guideBox.find('form').submit(function() {
			addGuide($(this).serialize());
			return false;
		});

	});

	guideBox.opened(function() {

		guideBox.find('textarea').autosize();

	});

	guideBox.closed(function() {

		guideBox.clearFormInputs();

	});

	function addGuide(serializedData) {
		guideBox.animateToTop();
		guideBox.setMessage(artguides.sending_msg);
		$.post(artguides.ajaxurl + '?' + serializedData, {action: 'new_artguide'}, function(data) {
			if(data.error_msg) {
				guideBox.setMessage(data.error_msg, 'error');
			} else {
				guideBox.setMessage(data.success_msg, 'success');
			}
		}, 'json');
	}

	/*
	 * Delete/remove UI stuff
	 */

	$(document).ready(function() {

		$('form#delete_artguide').submit(function() {
			deleteGuide($(this).serialize(), $(this).parents('article'));
			return false;
		});

	});

	function deleteGuide(serializedData, $el) {
		var r = confirm(artguides.confirm_delete);
		if(!r)
			return false;
		$.post(artguides.ajaxurl + '?' + serializedData, {action: 'delete_guide'}, function(data) {
			if(data.error_msg) {
				alert(data.error_msg);
			} else {
				$el.fadeOut('fast', function() { $(this).remove(); });
			}
		}, 'json');
	}

	/*
	 * Jeditable
	 */

	$(document).ready(function() {

		$('[data-editable="1"]').each(function() {
			$(this).parents('a').click(function(e) { e.preventDefault(); });
			var options = {
				submitdata: {
					action: 'jeditable_artguides',
					post_id: $(this).data('postid')
				},
				name: $(this).data('content'),
				type: $(this).data('type'),
				cancel: jeditable.cancel,
				submit: jeditable.submit,
				tooltip: jeditable.tooltip,
				placeholder: jeditable.tooltip,
				indicator: jeditable.saving,
				event: 'dblclick'
			}
			if($(this).data('type') == 'wysiwyg') {
				options.onblur = 'ignore';
				options.height = 'auto';
				options.wysiwyg = {
					rmUnusedControls: true,
					css: jeditable.css
				}
			}
			$(this).editable(jeditable.ajaxurl, options);
		});

	});

})(jQuery);