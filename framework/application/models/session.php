<?php
class Session extends Model {
	var $hasOne = array(
		'User'=>'User'
	);
}
?>