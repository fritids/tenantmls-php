var map;
function renderMap() {
	// The overlay layer for our marker, with a simple diamond as symbol
    map = new OpenLayers.Map("map");
    map.addLayer(new OpenLayers.Layer.OSM());
 
    var lonLat = new OpenLayers.LonLat( -73.100019 ,40.731971)
          .transform(
            new OpenLayers.Projection("EPSG:4326"), // transform from WGS 1984
            map.getProjectionObject() // to Spherical Mercator Projection
          );
 
    var zoom=15;
 
    var markers = new OpenLayers.Layer.Markers( "Markers" );
    map.addLayer(markers);
 
    markers.addMarker(new OpenLayers.Marker(lonLat));
 
    map.setCenter (lonLat, zoom);
}
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
	// Add the background
	$('body').prepend('<div class="ui-popup-background"></div>');
	$('.ui-popup-background').css({'width':window.innerWidth,'height':window.innerHeight}).fadeIn('fast');

	// Add the popup
	$('body').prepend('<div class="ui-popup"></div><div class="ui-popup-loader"></div>');

	// Show the popup loading
	$('.ui-popup-loader').html('<div class="ui-framework-ajax-loader" style="margin-top:40px;"></div>').center();

	// Hide the real popup and render in the background
	$('.ui-popup-loader').load(url);
	$('.ui-popup').hide();
	$('.ui-popup-loader').animate(
		{'width':x,
		'height':y,
		'top' : Math.max(0, (($(window).height() - y) / 3) + $(window).scrollTop()) + "px",
		'left' : Math.max(0, (($(window).width() - x) / 2) + $(window).scrollLeft()) + "px"
	},500);

	// Grow the popup-loader and fill in with the html of the real popup
	
	// If we click the background, shut down the window
	$('.ui-popup-background').click(function() {
		$('.ui-popup').hide().remove();
		$('.ui-popup-loader').hide().remove();
		$(this).hide().remove();
	});
}
$(document).ready(function() {
	var application = $('#application').html();
	/* MENU ITEMS */
	$('a').click(function() {
		window.history.pushState(application, null, $(this).attr('href'));
	});
	var open_menu = false
	$(document).click(function() {
		if (open_menu) {
			$('li.open-dropdown').attr('id','');
			$('div#dropdown_menu').slideUp('fast');
			open_menu = false;
		}
	});
	$('li.open-dropdown').click(function() {
		if (!open_menu) {
			var leftMargin = ($(this).width() - $('div#dropdown_menu').width())/2;
			$(this).attr('id','active');
			$('div#dropdown_menu').css({'margin-left':leftMargin}).slideDown('fast');
			open_menu = true;
		} else {
			$(this).attr('id','');
			$('div#dropdown_menu').slideUp('fast');
			open_menu = false;
		}
		return false;
	});
	$('div.ui-dropdown-menu ul li').click(function() {
		window.location = $(this).children('a').attr('href');
	});
	renderMap();
});
