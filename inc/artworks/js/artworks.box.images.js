(function($) {

	var container;

	$(document).ready(function() {

		container = $('#artwork_images_box');

		if(!container.length)
			return false;

		var list = container.find('.image-list');
		var template = container.find('.template');

		list.find('.remove').click(function() {
			$(this).parents('li').remove();
			updateAttrs();
			return false;
		});

		container.find('.new-image').click(function() {

			var item = template.clone().removeClass('template');

			item.find('.remove').click(function() {
				$(this).parents('li').remove();
				updateAttrs();
				return false;
			});

			list.append(item);

			updateAttrs();

			return false;

		});

		function updateAttrs() {
			list.find('li').each(function(i) {
				var id = 'image-' + i;
				$(this).find('.image-title').attr('name', 'artwork_images[' + id + '][title]');
				$(this).find('.image-file').attr('name', 'artwork_image_files[]');
				$(this).find('.image-id').attr('name', 'artwork_images[' + id + '][id]');
				$(this).find('.image-id').val(id);
				$(this).find('.featured-input').val(id);
				$(this).find('.featured-input').attr('id', 'featured_image_' + id);
				$(this).find('.featured-label').attr('for', 'featured_image_' + id);
			});
		}

	});

})(jQuery);