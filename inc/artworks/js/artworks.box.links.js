(function($) {

	var container;

	$(document).ready(function() {

		container = $('#artwork_links_box');

		if(!container.length)
			return false;

		var list = container.find('.link-list');
		var template = container.find('.template');

		list.find('.remove').click(function() {
			$(this).parents('li').remove();
			updateAttrs();
			return false;
		});

		container.find('.new-link').click(function() {

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
				$(this).find('.link-title').attr('name', 'artwork_links[' + i + '][title]');
				$(this).find('.link-url').attr('name', 'artwork_links[' + i + '][url]');
				$(this).find('.link-id').attr('name', 'artwork_links[' + i + '][id]');
				$(this).find('.link-id').val(i);
				$(this).find('.featured-input').val(i);
				$(this).find('.featured-input').attr('id', 'featured_link_' + i);
				$(this).find('.featured-label').attr('for', 'featured_link_' + i);
			});
		}

	});

})(jQuery);