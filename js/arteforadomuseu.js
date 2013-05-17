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

	/*
	 * Image gallery
	 */

	$(document).ready(function() {

		Shadowbox.init();

		var list = $(this).find('.image-list');
		var stage = $(this).find('.image-stage');

		updateStageDimensions();
		updateImageListDimensions();

		$(window).resize(function() {
			updateStageDimensions();
		});

		$(window).resize(function() {
			updateImageListDimensions();
		});

		$('.image-gallery').each(function() {

			list.find('a').click(function() {

				var full_url = $(this).data('full');

				var stageImage = $('<a href="' + full_url + '" rel="shadowbox"><img src="' + $(this).attr('href') + '" /></a>');

				stage.empty().append(stageImage);

				stageImage.click(function() {

					Shadowbox.open({
						content: full_url,
						player: 'img'
					});

					return false;

				});

				updateStageDimensions();

				return false;

			});

		});

		function updateStageDimensions() {

			stage.find('a').css({
				height: stage.parent().height()
			});
		}

		function updateImageListDimensions() {

			var size = 0;

			list.find('li').each(function() {
				size = size + $(this).height() + 10;
			});

			list.css({
				width: size
			});

			list.find('li').each(function() {
				$(this).css({
					width: $(this).height()
				});
			})

		}
	})

})(jQuery);