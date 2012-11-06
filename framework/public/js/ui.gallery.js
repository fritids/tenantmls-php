$(document).ready(function() {
	$('.uploadNew').click(function() {
		$('#ui-gallery-iframe').attr('src',TenantMLS.BASE_PATH+'/pictures/upload/'+$(this).attr('id'));
		//return false;
	});
	$('#ui-gallery-pictures li').click(function() {
		var url = $(this).children('img').attr('src').split('/');
		var array_count = url.length;
		var user_id = url[array_count-2];
		var file_name = url[array_count-1].substring(0,32);
		$('#ui-gallery-iframe').attr('src',TenantMLS.BASE_PATH+'/pictures/view/'+user_id+'/'+file_name);
	});

	$('li#deletePic').click(function() {
		$.post(TenantMLS.BASE_PATH+"/pictures/delete", {
			user_id : $('input[name="user_id"]').val(),
			picture_id : $('input[name="picture_id"]').val()
		},function() {
			parent.window.location.reload(true);
		});
	});
	
	$('li#setDefault').click(function() {
		$.post(TenantMLS.BASE_PATH+"/pictures/set_default", {
			user_id : $('input[name="user_id"]').val(),
			picture_id : $('input[name="picture_id"]').val()
		},function() {
			parent.window.location.reload(true);
		});
	});
	
	$('#content-take-picture').hide();
	$('a#showTakePicture').click(function() {
		$('#content-upload-file').hide();
		$('#content-take-picture').show();
	});
});