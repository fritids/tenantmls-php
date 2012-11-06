$(function() {
	$( "#locale" ).autocomplete({
		source: function( request, response ) {
			$.ajax({
				url: "http://ws.geonames.org/searchJSON?country=USA",
				dataType: "jsonp",
				data: {
					featureClass: "P",
					style: "full",
					maxRows: 12,
					name_startsWith: request.term
				},
				success: function( data ) {
					response( $.map( data.geonames, function( item ) {
						return {
							label: item.name + (item.adminName1 ? ", " + item.adminName1 : "") + ", " + item.countryName,
							value: item.name
						}
					}));
				}
			});
		},
		minLength: 2,
		select: function( event, ui ) {
			log( ui.item ?
				"Selected: " + ui.item.label :
				"Nothing selected, input was " + this.value);
		},
		open: function() {
			$( this ).removeClass( "ui-corner-all" ).addClass( "ui-corner-top" );
		},
		close: function() {
			$( this ).removeClass( "ui-corner-top" ).addClass( "ui-corner-all" );
		}
	});
});
function jumpTo(path,confirmMessage) {
	var answer = confirm(confirmMessage);
	if (answer == 1)	{
		location.href = path;
	}
}
function trim(stringToTrim) {
	return stringToTrim.replace(/^\s+|\s+$/g,"");
}
function ltrim(stringToTrim) {
	return stringToTrim.replace(/^\s+/,"");
}
function rtrim(stringToTrim) {
	return stringToTrim.replace(/\s+$/,"");
}

function ajaxOpenWindow(url,x,y) {
	$('body').prepend('<div class="ui-popup"></div>');
	$('.ui-popup').hide().load(url);
	if (x && !y) {
		$('.ui-popup').css("position","absolute");
    	$('.ui-popup').css("height", $(window).height() - 40);
    	$('.ui-popup').css("margin-top", 20);
    	$('.ui-popup').css("left", Math.max(0, (($(window).width() - x) / 2) + $(window).scrollLeft()) + "px");
	}
	if (x && y)
		$('.ui-popup').center(x,y);
	$('body').prepend('<div class="ui-popup-background"></div>');
	$('.ui-popup-background').css({'width':window.innerWidth,'height':window.innerHeight}).fadeIn('fast');
	$('.ui-popup').fadeIn();
	$('.ui-popup-background').click(function() {
		$('.ui-popup').hide().remove();
		$(this).hide().remove();
	});
}
function hideProfileTabs() {
	$('li#profile-content-Dashboard').hide();
	$('li#profile-content-Overview').hide();
	$('li#profile-content-Forms').hide();
	$('li#profile-content-MyTenants').hide();
	$('li#profile-content-Verification').hide();
	$('li#profile-content-Reviews').hide();
}