(function($) {

	var $box;

	$(document).ready(function() {

		$box = $('#add_artwork');

		$('.add_artwork').click(function() {
			openBox($(this).data('artwork'), $(this).data('artwork-title'));
			return false;
		});

		$box.find('.close-box').click(function() {
			closeBox();
		});

		$box.find('form').submit(function() {
			addArtwork($(this).serialize());
			return false;
		});

		$('form#remove_artwork').submit(function() {
			removeArtwork($(this).serialize(), $(this).parents('li'));
			return false;
		})

		$('form#delete_artguide').submit(function() {
			deleteArtguide($(this).serialize(), $(this).parents('article'));
			return false;
		})

	});

	var openBox = function(artworkID, artworkTitle) {
		$box.find('h2 .title').text(artworkTitle);
		$box.find('input.artwork_id').val(artworkID);
		$box.show();
	};

	var closeBox = function() {
		$box.find('h2 .title, .error-message').text('');
		$box.find('input[type=text],textarea').val('');
		$box.find('option').attr('selected', false);
		$box.find('.lightbox_content').scrollTop(0);
		$box.find('.error-message').remove();
		$box.hide();
	};

	var error = function(msg) {
		if(!$box.find('.error-message').length)
			$('<p class="error-message" />').insertAfter($box.find('h2'));

		$box.find('.lightbox_content').scrollTop(0);
		$box.find('.error-message').text(msg);
	}

	var addArtwork = function(serializedData) {
		$.post(artguides.ajaxurl + '?' + serializedData, {action: 'add_artwork_to_guide'}, function(data) {
			if(data.error_msg) {
				error(data.error_msg);
			} else {
				alert('success');
				//success(data.success);
				//closeBox();
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

	var deleteArtguide = function(serializedData, $el) {
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