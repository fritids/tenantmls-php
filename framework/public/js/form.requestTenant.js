$(document).ready(function() {
	$('input[name="request_tenant"]').click(function() {
		var user_id = $('input[name="user_id"]').val();
		$.post('/messages/request_tenant/'+user_id, {
			post_request : 1,
			user_id : user_id,
			body : $('textarea[name="body"]').val(),
			request_type : $('input[name="request_type"]').val(),
			request_commission : $('select[name="request_commission"]').val(),
			request_privileges : $('input[name="request_privileges_view"]:checked').length+','+$('input[name="request_privileges_edit"]:checked').length+','+$('input[name="request_privileges_upload"]:checked').length
		}, function(data) {
			alert(data);
		});
		window.location.reload(true);
	});
	
	$('input[name="request_type"]').change(function() {
		var xo = $(this).val();
		if (xo=='X') {
			$('<tr class="message_append"><td>Exclusive Tenant Form</td></tr><tr class="message_append"><td><input type="file" name="upload_file" /></td></tr>').insertAfter('#message_body');
		} else {
			$('.message_append').remove();
		}
	});
});
