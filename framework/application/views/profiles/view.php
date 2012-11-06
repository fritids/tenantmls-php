<?php echo $html->includeCss('ui.profile'); ?>
<?php // Check loggedIn
$access = false;

$loggedIn = false;
if (isset($xUserData)) {
	foreach ($xUserData['Session'] as $session) {
		if ($session['Session']['uniq'] == $_COOKIE['tmls_uniq_sess'])
			$loggedIn = true;
	}
}
// Check for myProfile
$myProfile = false;
if ($loggedIn) {
	foreach ($xUserData['Session'] as $session) {
		if ($session['Session']['user_id'] == $data['User']['id'])
			$myProfile = true;
	}
}
$agentTenantAccess = false;
echo '<input type="hidden" name="x-user_id" value="' . $data['User']['id'] . '" />';
if (isset($xUserData)) {
	foreach ($data['Privilege'] as $privilege) {
		if ($privilege['privileges_users']['user_id'] == $data['User']['id'])
			$agentTenantAccess = true;
	}
} else {
	$agentTenantAccess = false;
}
if ($agentTenantAccess || $myProfile)
	$access = true;
?>
<script>
	var link_open = false;
	//EDIT
	$('a.edit_link').click(function() {
		if (!link_open) {
			var cancelHTML = $(this).parent().attr('id','editing').html();
			var span = $(this).parent().attr('id','editing').html('<div class="ui-framework-ajax-loader"></div>').load('/tmls/profiles/edit/'+$(this).attr('id'));
			link_open = true;
		}
		return false;
	});
	//add
	$('a.add_employer').click(function() {
		if (!link_open) {
			var cancelHTML = $(this).parent().attr('id','editing').html();
			$(this).parent().parent().children('td:first-child').text('Add Employer');
			var span = $(this).parent().attr('id','editing').html('<div class="ui-framework-ajax-loader"></div>').load('/tmls/employers/add/'+$(this).attr('id'));
			link_open = true;
		}
		return false;
	});
</script>
<?php
$fields = array();
if (isset($data) && $data['User']['user_type']) {
	$fields = array(
		'Description'=>array('description'),
		'General'=>array(
			'tmls',
			'name',
			'agency_name',
			'current_locale',
			'agent_status',
			'license',
		),
		'Contact'=>array(
			'email',
			'phone_cell',
			'phone_work'
		)
	);
} else {
	// tenant
	$fields = array(
		'Description'=>array('description'),
		'General'=>array(
			'tmls',
			'name',
			'occupants',
			'pets',
			'can_afford',
			'desired_beds',
			'desired_locale',
			'move_date',
			'credit',
		),
		'Income'=>array(
			'income'
		),
		'Contact'=>array(
			'email',
			'phone_cell',
			'phone_home'
		),
		'References'=>array(
			'references'
		),
		'Other'=>array(
			'current_locale',
			'gender',
			'dob',
			'desired_baths',
			'desired_amenities',
			'program',
		)
	);
}
echo '<div id="ui-profile-seperator"></div>';
$titleArray = array();
$str = '<table id="overview-information">';
foreach($fields as $title=>$field) {
	if (!in_array($title,$titleArray) && $title!='Description') {
		$str.= '<tr class="overview-information-title">
			        <td>'.$title.'</td><td></td>
			    </tr>';
		array_push($titleArray,$title);
	}
	foreach($field as $field) {
		$edit = ($access) ? '<a href="javascript:void(0)" class="edit_link" id="'.$field.'/'.$data['User']['id'].'">Edit</a>' : '';
		switch($field) {
			case 'description' :
			if ($data['Profile']['description'])
				$description = str_replace("\n", "<br />", $data['Profile']['description']);
			elseif ($access && !$data['Profile']['description'])
				$description = 'No description.<br /><br /><i>Click <b>Edit</b> to add a description.</i>';
			elseif (!$access && !$data['Profile']['description'])
				$description = 'No description';
			
				$str.= '<tr class="editable">
					        <td>About Me</td><td>'.$edit.$description.'</td>
					    </tr>';
			break;
			case 'tmls' :
				$str.= '<tr>
					        <td>TMLS #</td><td>'.$data['User']['id'].'</td>
					    </tr>';
			break;
			case 'name' :
				$str.= '<tr class="editable">
							<td>Full Name</td>
							<td>'.$edit;
				$name = '';
	    		if ($data['Profile']['first_name'])
					$name.= $data['Profile']['first_name'].' ';
				if ($data['Profile']['middle_name'])
					$name.= $data['Profile']['middle_name'].' ';
				if ($data['Profile']['last_name'])
					$name.= $data['Profile']['last_name'];
				$str.= $name;
				$str.= '</td></tr>';
			break;
			case 'can_afford' :
				$str.= '<tr class="editable">
					        <td>Can Afford</td><td>'.$edit.'$'.$data['Profile']['tenant_max_rent'].'</td>
					    </tr>';
			break;
			case 'desired_beds' :
				$desired_beds = ($data['Profile']['tenant_desired_beds']>0) ? $data['Profile']['tenant_desired_beds'] : 'Studio';
				$str.= '<tr class="editable">
					        <td>Desired Bedrooms</td><td>'.$edit.$desired_beds.'</td>
					    </tr>';
			break;
			case 'desired_locale' :
				$str.= '<tr class="editable">
					        <td>Desired Locations</td><td>'.$edit;
				$stateTitleLocale = array();
				foreach($data['Locale'] as $locale) {
					$stateTitleLocale[$locale['Locale']['city_state']][$locale['Locale']['city_zip']] = $locale['Locale'];
				}
				foreach($stateTitleLocale as $state=>$cities) {
					$str.= '<span class="ui-profile-locale-state-title"><a href="'.BASE_PATH.'/locales/search/'.$state.'">'.returnStates($state).'</a></span><span class="ui-profile-locale-cities">';
					foreach($cities as $city) {
						$str.= '<a href="'.BASE_PATH.'/locales/search/'.$city['city_state'].'-'.$city['city_name'].'-'.$city['city_zip'].'/">'.$city['city_name'].'</a>, ';
					}
					$str = substr($str,0,strlen($str)-2);
					$str.= '</span>';
				}
				$str.= '</td></tr>';
			break;
			case 'occupants' :
				$str.= '<tr class="editable">
					        <td>Occupants</td><td>'.$edit;
				$str.= ($data['Profile']['tenant_occupants_adults']==1) ? $data['Profile']['tenant_occupants_adults'].' adult' : $data['Profile']['tenant_occupants_adults'].' adults';
				if ($data['Profile']['tenant_occupants_children']) {
					if ($data['Profile']['tenant_occupants_children']==1)
						$str.= ', '.$data['Profile']['tenant_occupants_children'].' child';
					else
						$str.= ', '.$data['Profile']['tenant_occupants_children'].' children';
				}
				$str.= '</td>
					    </tr>';
			break;
			case 'pets' :
				$str.= '<tr class="editable">
					        <td>Pets</td><td>'.$edit;
				if ($data['Profile']['tenant_pets_dogs']) {
					if ($data['Profile']['tenant_pets_dogs']==1)
						$str.= $data['Profile']['tenant_pets_dogs'].' dog, ';
					else
						$str.= $data['Profile']['tenant_pets_dogs'].' dogs, ';
				}
				if ($data['Profile']['tenant_pets_cats']) {
					if ($data['Profile']['tenant_pets_cats']==1)
						$str.= $data['Profile']['tenant_pets_cats'].' cat, ';
					else
						$str.= $data['Profile']['tenant_pets_cats'].' cats, ';
				}
				if ($data['Profile']['tenant_pets_other']) {
					if ($data['Profile']['tenant_pets_other']==1)
						$str.= $data['Profile']['tenant_pets_other'].' other pet, ';
					else
						$str.= $data['Profile']['tenant_pets_other'].' other pets, ';
				}
				if ($data['Profile']['tenant_pets_dogs'] || $data['Profile']['tenant_pets_cats'] || $data['Profile']['tenant_pets_other'])
					$str = substr($str,0,strlen($str)-2);
				else
					$str.= 'No pets';
					
				$str.= '</td>
					    </tr>';
			break;
			case 'move_date' :
				$str.= '<tr class="editable">
					        <td>Move-in Date</td><td>'.$edit;
				$str.= ($data['Profile']['tenant_move_date']) ? date("F d, Y", strtotime($data['Profile']['tenant_move_date'])) : 'N/A';
				$str.= '</td>
					    </tr>';
				break;
			case 'credit' :
				$str.= '<tr class="editable">
					        <td>Credit Score</td><td>'.$edit;
				if ($data['Profile']['tenant_credit_score']) {
					$str.= $data['Profile']['tenant_credit_score'];
				}
				if ($data['Profile']['tenant_credit_snapshot']) {
					$str.= ($data['Profile']['tenant_credit_score']) ? ' (' : '';
					switch($data['Profile']['tenant_credit_snapshot']) {
						case 1 : $str.= 'Marginal'; break;
						case 2 : $str.= 'Good'; break;
						case 3 : $str.= 'Excellent'; break;
					}
					$str.= ($data['Profile']['tenant_credit_score']) ? ') ' : '';
				}
				if (!$data['Profile']['tenant_credit_score'] && !$data['Profile']['tenant_credit_snapshot'])
					$str.= 'N/A';
				$str.= '</td>
					    </tr>';
			break;
			case 'email' :
				$str.= '<tr class="editable">
					        <td>Email</td><td>';
				$str.= ($data['Profile']['contact_email']) ? $data['Profile']['contact_email'] : 'N/A';
				$str.= '</td>
					    </tr>';
			break;
			case 'phone_cell' : 
				$str.= '<tr class="editable">
					        <td>Cell Phone</td><td>';
				$str.= ($data['Profile']['contact_cell']) ? formatPhone($data['Profile']['contact_cell']) : 'N/A';
				$str.= '</td>
					    </tr>';
			break;
			case 'phone_home' :
				$str.= '<tr class="editable">
					        <td>Home Phone</td><td>';
				$str.= ($data['Profile']['contact_home']) ? formatPhone($data['Profile']['contact_home']) : 'N/A';
				$str.= '</td>
					    </tr>';
			break;
			case 'phone_work' :
				$str.= '<tr class="editable">
					        <td>Work Phone</td><td>';
				$str.= ($data['Profile']['contact_work']) ? formatPhone($data['Profile']['contact_work']) : 'N/A';
				$str.= '</td>
					    </tr>';
			break;
			
			case 'income' :
				$add = ($access) ? '<a href="javascript:void(0)" class="add_employer" id="'.$data['User']['id'].'">+ Add new employer</a>' : '';
				foreach($data['Employer'] as $employer) {
					$str.= '<tr><td>';
					if ($employer['Employer']['pay_amount']) {
						$str.='<b>$'.$employer['Employer']['pay_amount'].' ';
						switch($employer['Employer']['pay_period']) {
							case 'W' : $str.= 'weekly'; break;
							case 'B' : $str.= 'biweekly'; break;
							case 'M' : $str.= 'monthly'; break;
							case 'Q' : $str.= 'quarterly'; break;
							case 'H' : $str.= 'semiannually'; break;	
							case 'A' : $str.= 'annually'; break;
						}
						$str.= '</b><br />';
					}
					$str.= '</td><td>';
					$noCompanyName = $noEmployerName = true;
					if ($employer['Employer']['company_name']) {
						$str.='<b>'.$employer['Employer']['company_name'].'</b><br />';
						$noCompanyName = false;
					}
					if ($employer['Employer']['employer_name']) {
						$bs = ($noCompanyName) ? '<b>' : '';
						$be = ($noCompanyName) ? '</b>' : '';
						$str.= $bs.$employer['Employer']['employer_name'].$be;
						$noEmployerName = false;
					}
					if ($employer['Employer']['employer_number']) {
						$str.= ($noEmployerName) ? '<br />' : ' - ';
						$str.= formatPhone($employer['Employer']['employer_number']);
					}
					$str.= ($employer['Employer']['upload_id']) ? '<br /><br />Proof of income' : '<br /><br />Proof not provided';
					$str.= '</td></tr>';
				}
				if (count($data['Employer'])==0) {
				$str.= '<tr class="editable">
					        <td>Employers not supplied</td><td>'.$add.'</td>
					    </tr>';
				} else {
					$str.= '<tr class="editable">
					        <td></td><td>'.$add.'</td>
					    </tr>';
				}
			break;
			
			case 'references' :
				foreach($data['Reference'] as $reference) {
					echo $reference['Reference']['name'];
				}
				if (count($data['Reference'])==0) {
				$str.= '<tr class="editable">
					        <td>Names not supplied</td><td></td>
					    </tr>';
				}
			break;
		}
	}
}
$str.= '</table>';
echo $str;
?>