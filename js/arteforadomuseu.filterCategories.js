/*
 * Filter categories inside map markers
 */

(function($) {

	var categories = afdmFilter.categories;
	var $filter;
	var $markers;
	var filtering = [];

	jeo.markersReady(function() {

		$filter = $('<ul class="category-filters" />');
		$.each(categories, function(i, cat) {

			if($('.map-container .story-points.category-' + cat.slug).length) {
				$filter.append('<li class="category-filter lsf-icon" title="checkboxempty" data-cat="' + cat.slug + '">' + cat.title + '</li>');
			}

		});

		$filter.on('click', 'li', function() {

			$(this).toggleClass('active');

			if($(this).is('.active'))
				$(this).attr('title', 'checkbox');
			else
				$(this).attr('title', 'checkboxempty');

			filter();

		});
		
		$('.map-container').append($filter);

	});

	function filter() {

		$markers = $('.map-container .story-points');

		filtering = [];

		$filter.find('.active').each(function() {

			filtering.push($(this).data('cat'));

		});

		console.log(filtering);

		if(filtering.length) {
			$markers.hide();
			$.each(filtering, function(i, f) {
				$('.map-container').find('.story-points.category-' + f).show();
			});
		} else {
			$markers.show();
		}

	}

})(jQuery);