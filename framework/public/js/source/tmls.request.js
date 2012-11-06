$(document).ready(function() {
	$('form[name="request_user"]').submit(function() {
		var data = $(this).serialize();
		$.post($(this).attr('action'),data,function() {
			$(window.parent.document).trigger("contact_added", {
	        	jid: $('input[name="jid"]').val(),
	        	name: $('input[name="name"]').val(),
	        	request_jid: $('input[name="request_jid"]').val(),
	        	request_name : $('input[name="request_name"]').val(),
	        	group: $('input[name="group"]').val()
	        });
		});
        return false;
    });

    $('form[name="respond_user"]').submit(function() {
		var data = $(this).serialize();
		$.post($(this).attr('action'),data,function() {
			$(window.parent.document).trigger("contact_approved", {
	        	jid: $('input[name="from_jid"]').val(),
	        	name: $('input[name="from_name"]').val(),
	        	from_jid: $('input[name="to_jid"]').val(),
	        	from_name: $('input[name="to_name"]').val(),
	        	group: $('input[name="group"]').val()
	        });
		});
        return false;
    });
});