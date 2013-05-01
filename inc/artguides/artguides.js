(function($) {

	var $artworkBox;
	var $guideBox;

	$(document).ready(function() {

		/*
		 * Add guide box
		 */

		$guideBox = $('#add_guide');

		$('.add_guide').click(function() {
			openGuideBox();
			return false;
		});

		$guideBox.find('.close-box').click(function() {
			closeGuideBox();
		});

		$guideBox.find('form').submit(function() {
			addGuide($(this).serialize());
			return false;
		});

		/*
		 * Add artwork to guide box
		 */

		$artworkBox = $('#add_artwork');

		$('.add_artwork').click(function() {
			openArtworkBox($(this).data('artwork'), $(this).data('artwork-title'));
			return false;
		});

		$artworkBox.find('.close-box').click(function() {
			closeArtworkBox();
		});

		$artworkBox.find('form').submit(function() {
			addArtwork($(this).serialize());
			return false;
		});

		/*
		 * Delete/remove UI stuff
		 */

		$('form#remove_artwork').submit(function() {
			removeArtwork($(this).serialize(), $(this).parents('li'));
			return false;
		});

		$('form#delete_artguide').submit(function() {
			deleteGuide($(this).serialize(), $(this).parents('article'));
			return false;
		});

		/*
		 * Jeditable
		 */

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

	var openGuideBox = function() {
		$guideBox.show();
		$guideBox.find('textarea').autosize();
	}

	var closeGuideBox = function() {
		$guideBox.find('input[type=text],textarea').val('');
		$guideBox.hide();
	}

	var openArtworkBox = function(artworkID, artworkTitle) {
		$artworkBox.find('h2 .title').text(artworkTitle);
		$artworkBox.find('input.artwork_id').val(artworkID);
		$artworkBox.show();
		$artworkBox.find('textarea').autosize();
	};

	var closeArtworkBox = function() {
		$artworkBox.find('h2 .title, .error-message').text('');
		$artworkBox.find('input[type=text],textarea').val('');
		$artworkBox.find('option').attr('selected', false);
		$artworkBox.find('.lightbox_content').scrollTop(0);
		$artworkBox.find('.error-message').remove();
		$artworkBox.hide();
	};

	var error = function(msg, $box) {
		if(!$box.find('.error-message').length)
			$('<p class="error-message" />').insertAfter($box.find('h2'));

		$box.find('.lightbox_content').scrollTop(0);
		$box.find('.error-message').text(msg);
	}

	var addArtwork = function(serializedData) {
		$.post(artguides.ajaxurl + '?' + serializedData, {action: 'add_artwork_to_guide'}, function(data) {
			if(data.error_msg) {
				error(data.error_msg, $artworkBox);
			} else {
				closeArtworkBox();
			}
		}, 'json');
	}

	var addGuide = function(serializedData) {
		$.post(artguides.ajaxurl + '?' + serializedData, {action: 'new_artguide'}, function(data) {
			if(data.error_msg) {
				error(data.error_msg, $guideBox);
			} else {
				closeGuideBox();
			}
		}, 'json');
	}

	var removeArtwork = function(serializedData, $el) {
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

	var deleteGuide = function(serializedData, $el) {
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

})(jQuery);