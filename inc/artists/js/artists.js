(function($) {

	/*
	 * Artwork <-> Artist interactions
	 */

	var artworkToArtistBox = afdmLightbox({containerID: 'add_artwork_to_artist'});

	var	artwork,
		artworkTitle;

	$(document).ready(function() {

		$('.add_artwork_to_artist').click(function() {
			artwork = $(this).data('artwork');
			artworkTitle = $(this).data('artwork-title');
			artworkToArtistBox.open();
			return false;
		});

		artworkToArtistBox.find('form').submit(function() {
			addArtworkToArtist($(this).serialize());
			return false;
		});

	});

	artworkToArtistBox.opened(function() {
		artworkToArtistBox.find('h2 .title').text(artworkTitle);
		artworkToArtistBox.find('input.artwork_id').val(artwork);
		artworkToArtistBox.find('textarea').autosize();
	});

	artworkToArtistBox.closed(function() {
		artworkToArtistBox.find('h2 .title, .error-message').text('');
		artworkToArtistBox.clearFormInputs();
	});

	function addArtworkToArtist(serializedData) {
		artworkToArtistBox.animateToTop();
		artworkToArtistBox.setMessage(artists.sending_msg);
		$.post(artists.ajaxurl + '?' + serializedData, {action: 'add_artwork_to_artist'}, function(data) {
			if(data.error_msg) {
				artworkToArtistBox.setMessage(data.error_msg, 'error');
			} else {
				artworkToArtistBox.setMessage(data.success_msg, 'success');
			}
		}, 'json');

	}

	/*
	 * Delete/remove UI stuff
	 */

	$(document).ready(function() {
		$('form#remove_artwork_from_artist').submit(function() {
			removeArtworkFromArtist($(this).serialize(), $(this).parents('li'));
			return false;
		});
	});

	function removeArtworkFromArtist(serializedData, $el) {
		var r = confirm(artists.confirm_delete);
		if(!r)
			return false;
		$.post(artists.ajaxurl + '?' + serializedData, {action: 'remove_artwork_from_artist'}, function(data) {
			if(data.error_msg) {
				alert(data.error_msg);
			} else {
				$el.fadeOut('fast', function() { $(this).remove(); });
			}
		}, 'json');
	}

	/*
	 * Delete/remove UI stuff
	 */

	$(document).ready(function() {

		$('form#delete_artist').submit(function() {
			deleteGuide($(this).serialize(), $(this).parents('article'));
			return false;
		});

	});

	function deleteArtist(serializedData, $el) {
		var r = confirm(artists.confirm_delete);
		if(!r)
			return false;
		$.post(artists.ajaxurl + '?' + serializedData, {action: 'delete_artist'}, function(data) {
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
					action: 'jeditable_artists',
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