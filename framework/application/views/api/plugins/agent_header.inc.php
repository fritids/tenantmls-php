<div id="contact-form-contact">
	<div id="contact-form-contact-picture">
		<?php
		if ((isset($params['TMLS']) && !empty($params['TMLS'])) || isset($xUserTmls)) {
			foreach($user['Picture'] as $pic) {
				if ($pic['default']==1)
					$picture = (isset($pic['thmb'])) ? $pic['thmb'] : BASE_PATH.'/images/ui-default-noPic.png';
			}
		}
		?>
		<img src="<?php echo $picture; ?>" />
	</div>
	<div id="contact-form-information">
		<?php
		if ((isset($params['TMLS']) && !empty($params['TMLS'])) || isset($xUserTmls)) {
			//printr($json_apidata);
			echo '<h5>'.$user['User']['first_name'].' '.$user['User']['middle_name'].' '.$user['User']['last_name'].'</h5>';
			echo '<span id="contact-form-agency">';
			echo 'Licensed Real Estate '.$user['User']['user_type'].'<br />';
			echo ($user['User']['agent_agency_name']) ? $user['User']['agent_agency_name'] : 'No agency';
			echo '</span>';
		}
		?>
	</div>
	<div class="clearfix"></div>
</div>