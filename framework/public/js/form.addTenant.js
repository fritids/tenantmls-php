$(document).ready(function() {
	$('select[name="addTenant-desired_locale_state"]').change(function() {
		$.post('/locales/ajax_county', {
			state : $(this).val()
		}, function(data) {
			$('select[name="addTenant-desired_locale_county"]').removeAttr('disabled').html(data);
		});
		$.post('/locales/ajax_city', {
			state : $(this).val()
		}, function(data) {
			$('select[name="addTenant-desired_locale_city"]').removeAttr('disabled').html(data);
		});
	});
	$('select[name="addTenant-desired_locale_county"]').change(function() {
		$.post('/locales/ajax_city', {
			county : $(this).val()
		}, function(data) {
			$('select[name="addTenant-desired_locale_city"]').removeAttr('disabled').html(data);
		});
	});
	$('select[name="addTenant-desired_locale_city"]').change(function() {
		$.post('/locales/ajax_county', {
			city : $(this).val()
		}, function(data) {
			$('select[name="addTenant-desired_locale_county"]').attr('disabled','disabled').html(data);
		});
	});
	
	$('form[name="addTenant"]').submit(function() {
		$.post('/users/add', {
			addTenant : 1,
			name : $('input[name="addTenant-name"]').val(),
			occupants_adults : $('input[name="addTenant-occupants_adults"]').val(),
			occupants_children : $('input[name="addTenant-occupants_children"]').val(),
			desired_beds : $('select[name="addTenant-desired_beds"]').val(),
			desired_baths : $('select[name="addTenant-desired_baths"]').val(),
			desired_locale_state : $('select[name="addTenant-desired_locale_state"]').val(),
			desired_locale_city : $('select[name="addTenant-desired_locale_city"]').val(),
			desired_locale_county : $('select[name="addTenant-desired_locale_county"]').val(),
			max_rent : $('input[name="addTenant-max_rent"]').val(),
			on_program : $('input[name="addTenant-on_program"]').val(),
			credit_snapshot : $('select[name="addTenant-credit_snapshot"]').val()
		}, function(data) {
			window.location = '/users/view/my_tenants';
		});
		return false;
	});
	
	// other navigation
	$('ul#ui-popup-tabs li').click(function() {
		$('ul#ui-popup-tabs li').attr('class','');
		$(this).attr('class','active');
		var content = $(this).attr('id').substr(4);
		$('ul#ui-popup-tabs-content li').attr('class','');
		$('ul#ui-popup-tabs-content li#content-'+content).attr('class','active').show();
	});
});
