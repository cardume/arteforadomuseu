(function($) {

	var container;

	$(document).ready(function() {

		container = $('#artwork_dates_box');

		if(!box_dates_settings.isAdmin)
			container.find('.chosen').chosen();

		if(!container.length)
			return false;

		$.datepicker.setDefaults($.datepicker.regional[box_dates_settings.language]);

		container.find('input.datepicker').datepicker({
			dateFormat: box_dates_settings.dateFormat,
			changeMonth: true,
			changeYear: true
		});

		container.find('#artwork_currently_active').attr('checked', true).attr('disabled', false);
		container.find('#artwork_date_termination').bind('change keyup keypress keydown', function() {
			if($(this).val())
				container.find('#artwork_currently_active').attr('checked', false).attr('disabled', true);
			else
				container.find('#artwork_currently_active').attr('checked', true).attr('disabled', false);

		});

	});

})(jQuery);