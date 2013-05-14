(function($) {

	var container;

	$(document).ready(function() {

		container = $('#artwork_videos_box');

		if(!container.length)
			return false;

		var list = container.find('.video-list');
		var template = container.find('.template');

		list.find('.remove').click(function() {
			$(this).parents('li').remove();
			updateAttrs();
			return false;
		});

		container.find('.new-video').click(function() {

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
				console.log(i);
				var id = 'video-' + i;
				$(this).find('.video-input').attr('name', 'videos[' + id + '][url]');
				$(this).find('.video-id').attr('name', 'videos[' + id + '][id]');
				$(this).find('.video-id').val(id);
				$(this).find('.featured-input').val(id);
				$(this).find('.featured-input').attr('id', 'featured_link_' + id);
				$(this).find('.featured-label').attr('for', 'featured_link_' + id);
			});
		}

	});

})(jQuery);