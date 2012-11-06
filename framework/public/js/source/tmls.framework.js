function capitaliseFirstLetter(string) {
    return string.charAt(0).toUpperCase() + string.slice(1);
}
function setInputSelection(input, startPos, endPos) {
    if (typeof input.selectionStart != "undefined") {
        input.selectionStart = startPos;
        input.selectionEnd = endPos;
    } else if (document.selection && document.selection.createRange) {
        // IE branch
        input.focus();
        input.select();
        var range = document.selection.createRange();
        range.collapse(true);
        range.moveEnd("character", endPos);
        range.moveStart("character", startPos);
        range.select();
    }
}

// left: 37, up: 38, right: 39, down: 40,
// spacebar: 32, pageup: 33, pagedown: 34, end: 35, home: 36
var keys = [37, 38, 39, 40];

function preventDefault(e) {
  e = e || window.event;
  if (e.preventDefault)
      e.preventDefault();
  e.returnValue = false;  
}

function keydown(e) {
    for (var i = keys.length; i--;) {
        if (e.keyCode === keys[i]) {
            preventDefault(e);
            return;
        }
    }
}

function wheel(e) {
  preventDefault(e);
}

function disable_scroll() {
  if (window.addEventListener) {
      window.addEventListener('DOMMouseScroll', wheel, false);
  }
  window.onmousewheel = document.onmousewheel = wheel;
  document.onkeydown = keydown;
}

function enable_scroll() {
    if (window.removeEventListener) {
        window.removeEventListener('DOMMouseScroll', wheel, false);
    }
    window.onmousewheel = document.onmousewheel = document.onkeydown = null;  
}

function getCookie(c_name)
{
var i,x,y,ARRcookies=document.cookie.split(";");
for (i=0;i<ARRcookies.length;i++)
{
  x=ARRcookies[i].substr(0,ARRcookies[i].indexOf("="));
  y=ARRcookies[i].substr(ARRcookies[i].indexOf("=")+1);
  x=x.replace(/^\s+|\s+$/g,"");
  if (x==c_name)
    {
    return unescape(y);
    }
  }
}

$(function() {
	$( "#locale" ).autocomplete({
		source: function( request, response ) {
			$.ajax({
				url: "http://api.geonames.org/postalCodeSearchJSON?username=tenantmls&country=USA",
				dataType: "jsonp",
				data: { maxRows: 7, placename_startsWith: request.term },
				success: function(data) {
					response($.map(data.postalCodes, function(item) {
						var place = item.placeName;
						var term = capitaliseFirstLetter(request.term);
						var re = new RegExp(term,'g');
						var replace = place.replace(re,'<span class="blue">'+term+'</span>');
						return {
							label: replace +  ", " + item.adminCode1 + ' ' + item.postalCode,
							value: place +  ", " + item.adminCode1 + ' ' + item.postalCode
						}
					}));
				}
			});
		},
		autoFocus: true,
		minLength: 2,
		focus: function(event, ui) { var oldVal = $(this).val(); var newVal = ui.item.value; $(this).val(newVal); setInputSelection(this,oldVal.length,newVal.length);},
		select: function(event, ui) { $(this).blur(); $(this).next('input').focus(); },
		open: function() { $( this ).removeClass( "ui-corner-all" ).addClass( "ui-corner-top" ); },
		close: function() { $( this ).removeClass( "ui-corner-top" ).addClass( "ui-corner-all" ); }
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
function open_menu(url,id) {

	$('body').prepend('<div class="ui-menu-background"></div>');
	$('.ui-menu-background').css({'width':window.innerWidth*0.99,'height':window.innerHeight}).fadeIn('fast');

	$('#'+id+'User').addClass('selected');

	$(document).find('#'+id+'-dialog').append('<div class="ui-menu" id="'+id+'-menu"></div>');
	$('#'+id+'-menu').html('<div class="ajax-loader"></div>');
	$('#'+id+'-menu').load(TenantMLS.BASE_PATH+'/'+url);

	$('.ui-menu-background').click(function() {
		$('#'+id+'User').removeClass('selected');
		$('#'+id+'-menu').hide().remove();
		$(this).hide().remove();
	});
}
function open_window(url) {
	// Add the background
	$('body').prepend('<div class="ui-popup-background"></div>');
	$('.ui-popup-background').css({'width':window.innerWidth,'height':window.innerHeight}).fadeIn('fast');

	// Add the popup
	$('body').prepend('<div class="ui-popup"></div><div class="ui-popup-loader"></div>');

	// Show the popup loading
	$('.ui-popup-loader').html('<div class="ajax-loader"></div>').center();

	// Hide the real popup and render in the background
	$('.ui-popup').hide().load(TenantMLS.BASE_PATH+url, function() {
		var y = $(this).height();
		var x = $(this).width();
		$('.ui-popup-loader').animate(
			{'width':x,
			'height':y,
			'top' : Math.max(0, (($(window).height() - y) / 3) + $(window).scrollTop()) + "px",
			'left' : Math.max(0, (($(window).width() - x) / 2) + $(window).scrollLeft()) + "px"
		},500,'linear',function() {
			$('.ui-popup').css({'width':x,
			'height':y,
			'top' : Math.max(0, (($(window).height() - y) / 3) + $(window).scrollTop()) + "px",
			'left' : Math.max(0, (($(window).width() - x) / 2) + $(window).scrollLeft()) + "px"}).show();
			$('.ui-popup-loader').hide().remove();
		});
	});
	//disable scroll
	$('html,body').css({'overflow': 'hidden'});
	//disable_scroll();
	
	// If we click the background, shut down the window
	$('.ui-popup-background').click(function() {
		$('.ui-popup').hide().remove();
		$('.ui-popup-loader').hide().remove();
		$(this).hide().remove();
		// enable scroll
		$('html,body').css({'overflow': 'auto'});
		//enable_scroll();
	});
}

$(window).bind("popstate", function(e){
	var state = event.state;
	if(state){
		updateSite(state.page);
	}else{
		updateSite("home");
	}
});

function updateSite(currentPage){
	var url = currentPage.split('/');
    $('#content').html('<div class="ajax-loader"></div>');
    $.post(TenantMLS.BASE_PATH+'/'+url[url.length-1]+'/', {
    	doNotRenderHeader:1,
    	inframe:1
    },function(data) {
    	$('#content').html(data);
    });
    //alert(currentPage);
}
function updateSiteViaSearch(currentPage){
	var url = currentPage.split('/');
    $('#content').html('<div class="ajax-loader"></div>');
    $.post(TenantMLS.BASE_PATH+'/'+url[url.length-3]+'/'+url[url.length-2]+'/', {
    	doNotRenderHeader:1,
    	inframe:1
    },function(data) {
    	$('#content').html(data);
    });
    //alert(currentPage);
}
$(document).ready(function() {
	var currentState = history.state;
	
	var sidebar_offset = $("#tmls-application-ui-sidebar").offset();
	var highlight_offset = $("#tmls-ui-profile-header").offset();
    var topPadding = 5;
    var sidebarPadding = 20;
    
    $(window).scroll(function() {
        if ($(window).scrollTop()+(sidebarPadding-10) > sidebar_offset.top) {
            $("#tmls-application-ui-sidebar").css({'margin-top' : $(window).scrollTop() - sidebar_offset.top + sidebarPadding});
        } else {
            $("#tmls-application-ui-sidebar").css({'margin-top' : '10px'});
        }
    });

    $(".e").click(function(e){
		e.preventDefault();
		var url = $(this).attr("href")+'/';
		history.pushState({page:url}, url, url);
		updateSiteViaSearch(url);
    });
	$(".a").click(function(e){
		$('.a').removeClass('active')
		$(this).addClass('active');
		e.preventDefault();
		var url = $(this).children('a').attr("href");
		history.pushState({page:url}, url, url);
		updateSite(url);
	});

	$(".tmls-ui-search #submit").click(function(){
		var terms = $('.tmls-ui-search #locale').val();
		terms = terms.split(' ').join('-');
		terms = terms.split(',').join('-');
		var url = TenantMLS.BASE_PATH+'/locales/search/'+terms;
		history.pushState({page:url}, url, url);
		updateSite(url);
	});
});