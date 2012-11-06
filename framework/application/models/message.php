<?php
class Message extends Model {
	var $hasManyAndBelongsToMany = array(
        'User'=>'User',
        );
}
?>