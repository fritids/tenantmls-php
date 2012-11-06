$(document).ready(function() {
	$('ul#ui-mainpage-tabs li').click(function() {
		$('ul#ui-mainpage-tabs li').attr('class','');
		$(this).attr('class','active');
		$('ul#ui-mainpage-tabs-content li').attr('class','');
		$('li#content-'+$(this).attr('id')).attr('class','active');
	});
	$('select[name="desired_locale-state"]').change(function() {
		$.post('/locales/ajax_city', {
			state : $(this).val()
		}, function(data) {
			$('select[name="desired_locale-city"]').html(data);
		});
	});
        $('select[name="desired_locale-city"]').change(function() {
            var select = $(this).val();
            if(select!='')
                $(this).attr('id','');
            else
                $(this).attr('id','inactive');
        });
});