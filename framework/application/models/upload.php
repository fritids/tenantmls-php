<?php
class Upload extends Model {
	function checkErrors($file=null) {
		if ($file['error'] === UPLOAD_ERR_OK) {
			return true;
		} else {
			return false;
		}
	}

	function uploadPicture($file, $width, $height) {
		
	}
}
?>