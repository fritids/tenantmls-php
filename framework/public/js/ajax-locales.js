$(document).ready(function() {
   /* AUTO-GUESS MAIN PAGE LOCALES */
    $('#ui-mainPage-locale').css('color','#CCCCCC').val('Enter a city, state, county, or zipcode');
    $('#ui-mainPage-locale').focus(function() {
    	if ($(this).val()=='Enter a city, state, county, or zipcode')
    		$(this).css('color','#000000').val('');
    });
    $('#ui-mainPage-locale').blur(function() {
    	if ($(this).val().length==0)
    		$(this).css('color','#CCCCCC').val('Enter a city, state, county, or zipcode');
    });
	$('#ui-mainPage-locale').keyup(function() {
		var keyCount = $(this).val().length;
		if (keyCount>=3) {
			var localeInput = $(this).val();
			$.getJSON('http://api.geonames.org/searchJSON?name_startsWith='+localeInput+'&country=US&username=tenantmls', function(data) {
			  
			$('ul#ui-mainPage-locale-results').html(data).show();
				
			});
		} else
			$('ul#ui-mainPage-locale-results').hide().html('');
			
			
	});
});