var afdmLightbox;

(function($) {

	afdmLightbox = function(options) {

		var settings = {
			containerID:			'',
			containerClass:			'',
			title:					'',
			content: 				'',
			timeout:				0
		};

		if(typeof options === 'undefined')
			options = {};

		settings = $.extend(settings, options);

		var content = $('<div class="lightbox_section" />');
		content.append('<div class="close-area close-box" style="cursor:pointer;"></div>');
		content.append('<div class="lightbox_container clearfix" />');

		var lightbox = content;

		var _init = function() {

			if(settings.containerID)
				lightbox.attr('id', settings.containerID);

			if(settings.containerClass)
				lightbox.attr('class', settings.containerClass);

			var alreadyThere = $('#' + settings.containerID);

			if(alreadyThere.length && !settings.title) {
				lightbox.find('.lightbox_container').append(alreadyThere.find('.lightbox_title'));
			} else {
				lightbox.find('.lightbox_container').append($('<h2 class="lightbox_title">' + settings.title + '</h2>'));
			}

			content.find('.lightbox_container').append('<div class="message" style="display:none;" />');

			if(alreadyThere.length && !settings.content) {
				lightbox.find('.lightbox_container').append(alreadyThere.find('.lightbox_content'));
			} else {
				lightbox.find('.lightbox_container').append($('<div class="lightbox_content">' + settings.content + '</div>'));
			}

			alreadyThere.remove();

			$('body').append(lightbox);

			lightbox.hide();

			_bindEvents();

			runCallbacks('ready', [lightbox]);

		}

		if($.isReady)
			_init();
		else
			$(document).ready(_init);

		var _bindEvents = function() {

			lightbox.on('click', '.close, .close-area', function() {
				lightbox.close();
				return false;
			});

			$(document).keyup(function(e) {
				var code = (e.keyCode ? e.keyCode : e.which);
				if(code == 27) {
					lightbox.close();
				}
			});

		}

		lightbox.open = function() {

			$('body').addClass('lightbox-opened');

			lightbox.clearMessage();

			lightbox.fadeIn('fast');
			runCallbacks('opened', [lightbox]);

			if(settings.timeout !== 0)
				setTimeout(lightbox.close, settings.timeout);

		}

		lightbox.close = function(timeout) {

			var _close = function() {
				lightbox.fadeOut('fast', function() {
					$('body').removeClass('lightbox-opened');
					lightbox.find('.lightbox_container').scrollTop(0);
					runCallbacks('closed', [lightbox]);
				});
			}

			if(typeof timeout !== 'undefined')
				setTimeout(_close, timeout);
			else
				_close();

		}

		lightbox.setContent = function(content) {
			lightbox.find('.lightbox_content').html(content);
		}

		lightbox.setTitle = function(title) {
			lightbox.find('.lightbox_title').html(title);
		}

		lightbox.messageType = '';

		lightbox.setMessage = function(msg, type) {

			lightbox.clearMessage();

			if(typeof type !== 'undefined')
				lightbox.messageType = type;

			lightbox.find('.message').show().addClass(lightbox.messageType).html(msg);

		}

		lightbox.clearMessage = function() {

			lightbox.find('.message').removeClass(lightbox.messageType);
			lightbox.messageType = '';

			lightbox.find('.message').hide().empty();

		}

		lightbox.animateToTop = function(speed, callback) {
			if(typeof speed === 'undefined')
				speed = 250;

			lightbox.find('.lightbox_container').animate({
				scrollTop: 0,
			}, speed, callback);
		}

		lightbox.clearFormInputs = function(maintain) {

			lightbox.find('input[type=text],input[type=file],input[type=radio],input[type=checkbox],input[type=hidden],textarea,option').each(function() {

				var el = $(this);

				if(el.is(maintain))
					return false;

				if(el.is('input[type=text],input[type=file],textarea')) {
					el.val('');

				} else if(el.is('option')) {
					el.attr('selected', false);

				} else if(el.is('input[type=radio],input[type=checkbox]')) {
					el.attr('checked', false).attr('disabled', false);

				}

			});
		}

		/*
		 * Callback manager
		 */

		var callbacks = {};

		var createCallback = function(name) {
			callbacks[name] = [];
			lightbox[name] = function(callback) {
				callbacks[name].push(callback);
			}
		}

		var runCallbacks = function(name, args) {
			if(!callbacks[name])
				return false;
			if(!callbacks[name].length)
				return false;

			var _run = function(callbacks) {
				if(callbacks) {
					_.each(callbacks, function(c, i) {
						if(c instanceof Function)
							c.apply(this, args);
					});
				}
			}
			_run(callbacks[name]);
		}

		createCallback('ready');
		createCallback('opened');
		createCallback('closed');

		return lightbox;

	}

})(jQuery);