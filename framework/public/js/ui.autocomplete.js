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