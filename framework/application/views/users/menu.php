<ul id="manage-menu">
	<?php
		if ($menu['User']['user_type']=='Agent') {
			$linkArray = array(
				'Statistics'=>BASE_PATH.'/users/statistics',
				'Activity'=>BASE_PATH.'/users/activity',
				'Conversation'=>BASE_PATH.'/users/conversation',
				'Information'=>BASE_PATH.'/users/information',
				'Agency'=>BASE_PATH.'/users/agency'
			);
		} else {
			$linkArray = array(

			);
		}
		foreach($linkArray as $text=>$link) {
			$onclick = 'onclick="open_window(\''.$link.'/'.$menu['User']['tmls_number'].'\')"';
			echo '<li><span '.$onclick.'>'.$text.'</span></li>';
		}
	?>
</div>