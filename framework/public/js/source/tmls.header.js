/* MENU ITEMS */
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