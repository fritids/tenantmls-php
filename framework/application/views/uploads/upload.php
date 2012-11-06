<form action="<?php echo BASE_PATH.'/uploads/upload/'.$object_id.'/'.$type; ?>" method="post" enctype="multipart/form-data" id="ui-picture-uploadForm">
	<input type="file" name="upload-file" />
	<input type="hidden" name="upload-user_id" value="<?php echo $profile['User']['tmls_number']; ?>" />
	<input type="hidden" name="MAX_FILE_SIZE" value="<?php echo MAX_FILE_SIZE; ?>" />
	<input type="submit" name="upload" id="ui-picture-uploadForm-submit" value="Upload" />
</form>