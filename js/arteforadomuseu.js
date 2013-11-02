(function($) {

	$(document).ready(function() {

		if($('#main-nav').length) {
			var navigation = responsiveNav('#main-nav', {
				label: '&#xE08e'
			});
		}

		$(window).scroll(function() {
			if($(window).scrollTop() >= 10) {
				$('#masthead').addClass('scrolled');
			} else {
				$('#masthead').removeClass('scrolled');
			}
		});

	});

	/*
	 * Carousels
	 */

	jeo.mapReady(function() {

		$('.carousel').each(function() {

			var carousel = $(this),
				autorun = !carousel.parents('.disable-autorun').length,
				items = carousel.find('li'),
				current = items.first(),
				controllers = carousel.find('.carousel-controllers');

			if(items.length === 1){
				controllers.hide();
			}

			show(current);

			if(autorun)
				var t = setInterval(next, 8000);

			controllers.on('click', 'a', function() {

				if($(this).is('.next'))
					next();
				else
					previous();

				return false;

			});

			function next() {

				hide(current);

				if(current.is('li:last-child'))
					current = items.first()
				else
					current = current.next('li');

				show(current);

			}

			function previous() {

				hide(current);

				if(current.is('li:first-child'))
					current = items.last()
				else
					current = current.prev('li');

				show(current);

			}

			function show(el) {
				if(autorun && t) {
					clearInterval(t);
					t = setInterval(next, 8000);
				}
				el.addClass('active');
			}

			function hide(el) {
				el.removeClass('active');
			}

		});

	});


	/*
	 * Subsection
	 */

	jeo.mapReady(function() {

		subsection.init();

		$('[data-subsection]').click(function() {

			subsection.open($(this).data('subsection'));
			return false;

		});

		$('.toggle-map').click(function() {

			var toggler = $(this);

			subsection.close();

			if($('.map-container .map').hasClass('open')) {
				$('.map-container .map').removeClass('open');
				toggler.find('.label').text(toggler.data('default-text'));
			} else {
				$('.map-container .map').addClass('open');
				toggler.find('.label').text(toggler.data('toggled-text'));
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

			this.close();

			this.subcontent = $('#' + id + '.sub-content');

			this.previousMapRight = $('.map-container').css('right');

			if(this.subcontent.length) {

				this.subcontent.show().addClass('active').css({
					right: 0
				});
				this.parent.css({
					right: this.subcontent.width()
				});

				$('.map-container').css({
					right: $('#content').width() * 2
				});

				jeo.map.invalidateSize(true);

			}

			window.location.hash = 'section=' + id;

			return this;
		},
		close: function() {

			this.parent.css({
				right: 0
			});

			this.subcontents.removeClass('active').css({
				right: -this.subcontents.width()
			});

			$('.map-container').css({
				right: this.previousMapRight
			});

			jeo.map.invalidateSize(true);

			window.location.hash = '';

			return this;

		}
	}

	/*
	 * Image gallery
	 */

	$(document).ready(function() {

		if($('.image-gallery').length) {

			Shadowbox.init();

			var list, stage;

			$('[data-subsection="images"]').click(function() {
				updateStageDimensions();
				updateImageListDimensions();
			});

			$(window).resize(function() {
				updateStageDimensions();
				updateImageListDimensions();
			});

			$('.image-gallery').each(function() {

				list = $(this).find('.image-list');
				stage = $(this).find('.image-stage');

				updateStageDimensions();
				updateImageListDimensions();

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

		}

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

	});

})(jQuery);