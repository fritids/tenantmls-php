<?php echo $html->includeCss('form.editProfile'); ?>
<?php echo $html->includeJs('form.editProfile'); ?>
<?php
$fields = array();
if (isset($xUserData) && $xUserData['User']['user_type']) {
	$fields = array(
		'General'=>array(
			'description',
			'name',
			'agency',
			'license',
			'coverage_area'
		),
		'Contact'=>array(
			'email',
			'phone_cell',
			'phone_work',
			'phone_home'
		),
		'Settings'=>array(
		
		)
	);
} else {
	// tenant
	$fields = array(
		'General'=>array(
			'looking_for',
			'desired_locale',
			'occupants',
			'move_date',
			'credit',
			'description'
		),
		'Contact'=>array(
			'email',
			'phone_cell',
			'phone_home'
		),
		'Income'=>array(
			'add_employer'
		),
		'References'=>array(
			'add_reference'
		),
		'Other'=>array(
			'current_locale',
			'gender',
			'dob',
			'desired_baths',
			'desired_amenities',
			'program',
		),
		'Settings'=>array(
			'name'
		)
	);
}
?>
<span class="ui-popup-header"> <h4>Profile & Settings</h4> </span>
<div id="ui-popup-editProfile">
	<ul id="ui-popup-editProfile-sidebar">
		<?php
		$titleArray = array();
		$str = $content = '';
		foreach($fields as $title=>$field) {
			if (!in_array($title,$titleArray)) {
				$active = ($title=='General')  ? ' id="active"' : '';
				$str.= '<li'.$active.'>'.$title.'</li>';
				array_push($titleArray,$title);
			}
			$content.= '<table id="ui-editProfile">';
			foreach($field as $field) {
				switch($field) {
					case 'description' :
						$content.= '<tr><td>';
						$content.= '<h4>About Me</h4><span class="contain">';
						$content.= ($xUserData['Profile']['description']) ? $xUserData['Profile']['description'] : 'No description' ;
						$content.= '</span></td></tr>';
					break;
					case 'name' :
						$content.= '<tr><td>';
						$content.= '<h4><a href="javascript:void(0)" id="name/'.$xUserData['User']['id'].'" class="edit">Edit</a>Full Name</h4><span class="contain">';
						$name = '';
			    		if ($xUserData['Profile']['first_name'])
							$name.= $xUserData['Profile']['first_name'].' ';
						if ($xUserData['Profile']['middle_name'])
							$name.= $xUserData['Profile']['middle_name'].' ';
						if ($xUserData['Profile']['last_name'])
							$name.= $xUserData['Profile']['last_name'];
						$content.= $name;
						$content.= '</span></td></tr>';
					break;
					case 'agency' :
						$content.= '<tr><td>';
						$content.= '<h4>Agency</h4><span class="contain">';
						$content.= ($xUserData['Profile']['agent_agency_name']) ? $xUserData['Profile']['agent_agency_name'] : 'No agency name' ;
						$content.= ($xUserData['Profile']['agent_agency_address']) ? '<br />'.$xUserData['Profile']['agent_agency_address'] : '<br />No address' ;
						$content.= ($xUserData['Profile']['contact_work']) ? '<br />'.formatPhone($xUserData['Profile']['contact_work']) : '<br />No phone #' ;
						$content.= '</span></td></tr>';
					break;
					case 'license' :
						$content.= '<tr><td>';
						$content.= '<h4><a href="javascript:void(0)" id="agent_license" class="edit">Edit</a>License</h4><span class="contain">';
						switch ($xUserData['Profile']['agent_status']) {
							case 0 : $content.= 'Licensed Real Estate Agent'; break;
							case 1 : $content.= 'Licensed Associate Broker'; break;
							case 2 : $content.= 'Licensed Real Estate Broker'; break;
							}
						$content.= ($xUserData['Profile']['agent_license_number']) ? '<br />'.$xUserData['Profile']['agent_license_number'] : '<br />No license #' ;
						$content.= ($xUserData['Profile']['agent_license_state']) ? '('.$xUserData['Profile']['agent_license_state'].')' : '' ;
						$content.= '</span></td></tr>';
					break;
				}
			}
			$content.= '</table>';
		}
		echo $str;
		?>
	</ul>
	<div id="ui-popup-editProfile-container">
		<?php
		$formElements = array();
		foreach($fields as $headers=>$inputs) {
			foreach($inputs as $input) {
				switch($input) {
					case 'agency_name' : $formElements['Agency Name'] = array($input,$form->formInput($input));
						break;
				}
			}
		}
		echo $content;
		//$formElements['Submit Changes'] = array('submit',$form->formSubmit('submit'));
		//echo $form->create(array('editProfile','/profiles/edit','POST'),$formElements,true,true);
		?>
	</div>
</div>