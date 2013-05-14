(function($) {

	$(document).ready(function() {

		var navigation = responsiveNav('#main-nav', {
			label: 'menu'
		});

		$(window).scroll(function() {
			if($(window).scrollTop() >= 10) {
				$('#masthead').addClass('scrolled');
			} else {
				$('#masthead').removeClass('scrolled');
			}
		});

	});

	/*
	 * Toggle map
	 */

	$(document).ready(function() {

		$('.toggle-map').click(function() {

			if($('.map-container .map').hasClass('open')) {
				$('.map-container .map').removeClass('open');
			} else {
				$('.map-container .map').addClass('open');
			}

		});

	});


	/*
	 * Subsection
	 */

	 $(document).ready(function() {

	 	subsection.init();

	 	$('[data-subsection]').click(function() {

	 		subsection.open($(this).data('subsection'));
	 		return false;

	 	});

	 });

	var subsection = {
		init: function() {

			var subcontents = $('.sub-content');

			if(subcontents.length) {

				subcontents.hide();

				positioning();

				subcontents.find('.close').click(function() {

					subsection.close();
					return false;

				});

				$(window).resize(positioning);

				function positioning() {
					subcontents.each(function() {
						if(!$(this).hasClass('active')) {
							subcontents.css({
								right: -subcontents.width()
							});
						} else {
							$('#content').css({
								right: subcontents.width()
							});
						}
					});
				}
			}

			return this;

		},
		open: function(id) {
			var subcontent = $('#' + id + '.sub-content');
			var parent = $('#content');

			if(subcontent.length) {

				subcontent.show().addClass('active').css({
					right: 0
				});
				parent.css({
					right: subcontent.width()
				});

			}

			return this;
		},
		close: function() {
			var subcontents = $('.sub-content');
			var parent = $('#content');

			parent.css({
				right: 0
			});

			subcontents.removeClass('active').css({
				right: -subcontents.width()
			});

			return this;
		}
	}

})(jQuery);