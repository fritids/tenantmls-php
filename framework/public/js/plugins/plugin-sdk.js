// Requires the JQuery plugin (v1.7.2+)
$(document).ready(function() {
	$("select, input:text, input:checkbox, input:radio, input:file").uniform();
	
	$('select[name="locale-state"]').change(function() {
		var val = $(this).val();
		$.ajax({
		  url: 'http://173.68.172.103:9090/tmls/locales/ajax_city/'+val+'/option',
		  success: function(data) {
		  	$('select[name="locale-city"]').html(data);
		  }
		});
	});
	$('select[name="locale-city"]').change(function() {
		$.post("http://173.68.172.103:9090/tmls/locales/add", {
			zip : $(this).val()
		}, function(data) {
			var split = data.split('$');
			$('select[name="locale-city"] option:first').attr('selected','SELECTED');
			$('ul#tenantfinder-sidebar-areas').append('<li><a class="remove" href="javascript:void(0)" id="'+split[0]+'">x</a> '+split[1]+'</li>');
			$('ul#tenantfinder-sidebar-areas li:last-child').hide().fadeIn('slow');
			$('ul#tenantfinder-sidebar-areas li').click(function() {
				$(this).fadeOut('slow');
				$.post("/locales/remove", {
					locale_id : $(this).children('a').attr('id')
				});
				return false;
			});
		});
		return false;
	});
});
