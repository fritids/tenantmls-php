<?php
class Picture extends Model {
	function checkErrors($file=null) {
		if ($file['error'] === UPLOAD_ERR_OK) {
			return true;
		} else {
			return false;
		}
	}
}

?>