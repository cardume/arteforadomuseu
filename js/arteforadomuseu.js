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
	 * Subsection
	 */

	$(document).ready(function() {

		var s = subsection.init();

		$('[data-subsection]').click(function() {

			subsection.open($(this).data('subsection'));
			return false;

		});

		$('.toggle-map').click(function() {

			subsection.close(s);

			if($('.map-container .map').hasClass('open')) {
				$('.map-container .map').removeClass('open');
			} else {
				$('.map-container .map').addClass('open');
			}

			return false;

		});

		var hash = window.location.hash;

		if(hash.indexOf('section') !== -1) {
			var section = hash.split('section=')[1];
			subsection.open(section);
		}

		if(hash.indexOf('comment') !== -1) {
			subsection.open('comments');
		}

	});

	var subsection = {
		init: function() {

			this.subcontents = $('.sub-content');
			this.parent = $('#content');

			var section = this;

			if(this.subcontents.length) {

				this.subcontents.hide();

				positioning();

				this.subcontents.find('.close').click(function() {
					section.close(section);
					return false;
				});

				$(window).resize(positioning);
			}

			function positioning() {

				section.subcontents.each(function() {
					if(!$(this).hasClass('active')) {
						$(this).css({
							right: -$(this).width()
						});
					} else {
						section.parent.css({
							right: $(this).width()
						});
					}
				});

			}

			return this;

		},
		open: function(id) {

			this.close(this);

			this.subcontent = $('#' + id + '.sub-content');

			if(this.subcontent.length) {

				this.subcontent.show().addClass('active').css({
					right: 0
				});
				this.parent.css({
					right: this.subcontent.width()
				});

			}

			window.location.hash = 'section=' + id;

			return this;
		},
		close: function(section) {

			section.parent.css({
				right: 0
			});

			section.subcontents.removeClass('active').css({
				right: -section.subcontents.width()
			});

			window.location.hash = '';

			return section;

		},
	}

})(jQuery);