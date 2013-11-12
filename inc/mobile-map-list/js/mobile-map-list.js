(function($) {

	var toggler,
		mapContainer,
		listContainer;

	$(document).ready(function() {
		toggler = $('.mobile-map-list-selector');
		mapContainer = $('.map-container');
		listContainer = $('#content');

		if(!mapContainer.length) {
			toggler.hide();
			return false;
		}

		$(window).resize(toggle).resize();

		toggler.find('a').click(function() {

			if($(this).is('.map-selector'))
				activate('map');
			else if($(this).is('.list-selector'))
				activate('list');

			return false;

		});

		$('.toggle-map,.find-on-map,.center-map').click(function() {
			if(isMobile())
				activate('map');
		});

	});

	function toggle() {

		if(isMobile()) {

			activate('map');
			toggler.show();

		} else {

			deactivate();
			toggler.hide();

		}

	}

	function isMobile() {
		return ($(window).width() < 767);
	}

	function activate(element) {

		if(element == 'map') {

			toggler.find('a').removeClass('active');
			toggler.find('a.map-selector').addClass('active');

			listContainer.removeClass('mobile-active').addClass('mobile-hidden');
			mapContainer.addClass('mobile-active').removeClass('mobile-hidden');

		} else if(element == 'list') {

			toggler.find('a').removeClass('active');
			toggler.find('a.list-selector').addClass('active');

			mapContainer.removeClass('mobile-active').addClass('mobile-hidden');
			listContainer.addClass('mobile-active').removeClass('mobile-hidden');

		}

		jeo.map.invalidateSize(true);

	}

	function deactivate() {

		listContainer.removeClass('mobile-active').removeClass('mobile-hidden');
		mapContainer.removeClass('mobile-active').removeClass('mobile-hidden');

	}
	
})(jQuery);