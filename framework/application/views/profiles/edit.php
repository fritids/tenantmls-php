<?php
#echo $html->includeJs('jquery-1.7.2.min');
?>
<script>
$('form[name="pedit"]').submit(function() {
	var formData = $(this).serialize();
	$.post(TenantMLS.BASE_PATH+'/profiles/update', formData, function(data) {
		if (data==1) {
			var url = TenantMLS.BASE_PATH+'/users/profile';
			history.pushState({page:url}, url, url);
			updateProfile(url.replace("profile","profile_inframe_noheader").replace("dashboard","dashboard_inframe"));
		}
	});
	return false;
});
</script>
<?php
$formElements = array();
$form = '<form action="'.BASE_PATH.'/profiles/update" method="POST" name="pedit">';
$form.= '<input type="hidden" name="user_id" value="'.$controllerUserId.'" />';
$form.= '<input type="hidden" name="field" value="'.$controllerField.'" />';

switch($controllerField) {
	/*
	 * NEW EDITs
	 */
	case 'aboutme' : 
		$form.= '<h4>About Me</h4><h5 class="esubtext">A personal description of you. You should talk about your experience level, why other people should work with you, your dedication to the customer, etc.</h5>';
		$form.= '<textarea name="description" cols="60" rows="16" class="textarea-fill">'.$profile['User']['description'].'</textarea>';
		break;
	case 'agency' :
		foreach($profile['Agency'] as $agency) {
			$form.= '<h4>Agency</h4>';
			$form.= '<h5 class="esubtext">The real estate company you currently work for</h5>';
			$form.= '<table class="employer">';
			$form.= '<tr>';
			$form.= '<td>Agency Name</td>';
			$form.= '<td><input type="text" name="company_name" value="'.$agency['company_name'].'" /></td>';
			$form.= '</tr>';
			$form.= '<tr>';
			$form.= '<td>Address</td>';
			$form.= '<td><input type="text" name="company_locale" value="'.$agency['company_locale'].'" /></td>';
			$form.= '</tr>';
			$form.= '<tr>';
			$form.= '<td>Phone Number</td>';
			$form.= '<td><input type="text" name="employer_number_1" value="'.substr($agency['employer_number'],0,3).'" size="3" />-';
			$form.= '<input type="text" name="employer_number_2" value="'.substr($agency['employer_number'],3,3).'" size="3" />-';
			$form.= '<input type="text" name="employer_number_3" value="'.substr($agency['employer_number'],6,4).'" size="4" />';
			$form.= '</td>';
			$form.= '</tr>';
			$form.= '<tr>';
			$form.= '<td>License Status</td>';
			$form.= '<td><select name="agent_license_status">';
			for($i = 0; $i <= 2; $i++) {
				if ($i==0) $oname = 'Agent';
				if ($i==1) $oname = 'Associate Broker';
				if ($i==2) $oname = 'Broker';
				$selected = ($agency['agent_license_status']==$oname) ? ' selected="selected"' : '';
				$form.= '<option value="'.$i.'"'.$selected.'>'.$oname.'</option>';
			}
			$form.= '</td>';
			$form.= '</tr>';
			$form.= '<tr>';
			$form.= '<td>License Number</td>';
			$form.= '<td><input type="text" name="company_name" value="'.$agency['agent_license_number'].'" /></td>';
			$form.= '</tr>';
			$form.= '</table>';

			$form.= '<iframe width="100%" height="100" src="'.BASE_PATH.'/uploads/upload/'.$agency['id'].'/employer-picture"></iframe>';
		}
	break;
	case 'desiredareas' :
		$form.= '<ul class="locales">';
		foreach($profile['DesiredLocale'] as $locale) {
			$form.= '<li class="locale"><span class="x">x</span>'.$locale['city_name'].', '.$locale['city_state'].'</li>';
		}
		$form.= '</ul>';
	break;
}
$form.= '<input type="submit" name="submit" value="Save" />';
echo $form;