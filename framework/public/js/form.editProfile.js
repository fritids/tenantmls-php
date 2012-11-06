$(document).ready(function() {
	// change the locale information
	$('select[name="desired_locale_state"]').change(function() {
		$.post('/locales/ajax_city', {
			state : $(this).val()
		}, function(data) {
			$('select[name="desired_locale_city"]').removeAttr('disabled').html(data);
		});
	});
	$('select[name="current_locale_state"]').change(function() {
		$.post('/locales/ajax_city', {
			state : $(this).val()
		}, function(data) {
			$('select[name="current_locale_city"]').removeAttr('disabled').html(data);
		});
	});
	// add cities
	$('select[name="desired_locale_city"]').change(function() {
		$.post("/locales/add_desired", {
			user_id : $('input[name="user_id"]').val(),
			zip : $(this).val()
		}, function(data) {
			var split = data.split('$');
			$('select[name="desired_locale_city"] option:first').attr('selected','SELECTED');
			$('ul#ui-popup-desired-location-list').append('<li><a class="remove_desired_locale" href="javascript:void(0)" id="'+split[0]+'">x</a> '+split[1]+'</li>');
			$('ul#ui-popup-desired-location-list li:last-child').hide().fadeIn('slow');
			$('ul#ui-popup-desired-location-list li').click(function() {
				$(this).fadeOut('slow');
				$.post("/locales/remove_desired", {
					locale_id : $(this).children('a').attr('id')
				});
				return false;
			});
		});
		return false;
	});
	$('ul#ui-popup-desired-location-list li').click(function() {
		$(this).fadeOut('slow');
		$.post("/locales/remove_desired", {
			locale_id : $(this).children('a').attr('id')
		});
		return false;
	});
	
	// edit function
	$('a.edit').click(function() {
		var span = $(this).parent().next('span').attr('id','editing').html('<div class="ui-framework-ajax-loader"></div>').load('/tmls/profiles/edit/'+$(this).attr('id'));
		return false;
	});
	
});
