<?php echo $html->includeCss('form.editProfile'); ?>
<?php echo $html->includeJs('form.editProfile'); ?>
<?php
$fields = array();
if (isset($xUserData) && $xUserData['User']['user_type']) {
	$fields = array(
		'General'=>array(
			'agency_name',
			'current_locale',
			'agent_status',
			'license',
			'description'
		),
		'Contact'=>array(
			'email',
			'phone_cell',
			'phone_work',
			'phone_home'
		),
		'Settings'=>array(
			'name'
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
<span class="ui-popup-header">
	<h4>Edit Profile</h4>
	<h5><span class="red">* fields required</span></h5>
</span>
<div id="ui-popup-editProfile">
	<form action="" method="post" name="editProfile" enctype="multipart/form-data">
		<input type="hidden" name="user_id" value="<?php echo $xUserData['User']['id']; ?>" />
		<input type="hidden" name="profile_id" value="<?php echo $xUserData['Profile']['id']; ?>" />
		<div id="ui-popup-tabsContainer">
			<ul id="ui-popup-tabs">
			<?php
			foreach($fields as $tab=>$field) {
				$selected = ($tab == 'General') ? ' class="active"' : '';
				echo '<li id="tab-'.strtolower(str_replace('/','',$tab)).'"'.$selected.'>'.$tab.'</li>';
			}
			?>
			</ul>
			<div class="clear"></div>
		</div>
		<ul id="ui-popup-tabs-content">
		<?php
		foreach($fields as $tab=>$field) {
			$str = '';
			$selected = ($tab == 'General') ? ' class="active"' : '';
			$str.= '<li id="content-'.strtolower(str_replace('/','',$tab)).'"'.$selected.'>';
			$str.= '<table class="ui-popup-editProfile-table">';
				foreach($field as $field) {
					switch($field) {
					case 'name' :
						$name = $xUserData['Profile']['first_name'];
						if ($xUserData['Profile']['middle_name'])
							$name.= ' '.$xUserData['Profile']['middle_name'];
						if ($xUserData['Profile']['last_name'])
							$name.= ' '.$xUserData['Profile']['last_name'];
						$str.= '<tr>';
						$str.= '<td>Name <span class="red">*</span></td>
								<td><input type="text" name="name" value="'.$name.'" /></td>';
						$str.= '</tr>';
						break;
					case 'agency_name' :
						$str.= '<tr>';
						$str.= '<td>Company <span class="red">*</span></td>
								<td><input type="text" name="agent_agency_name" value="'.$xUserData['Profile']['agent_agency_name'].'" /></td>';
						$str.= '</tr>';
						break;
					case 'agent_status' :
						$agentSelected = ($xUserData['Profile']['agent_status']==0) ? ' selected="SELECTED"' : '';
						$assocBrokerSelected = ($xUserData['Profile']['agent_status']==1) ? ' selected="SELECTED"' : '';
						$brokerSelected = ($xUserData['Profile']['agent_status']==2) ? ' selected="SELECTED"' : '';
						$str.= '<tr>';
						$str.= '<td>Status <span class="red">*</span></td>
								<td><select name="agent_status">
									<option value="0"'.$agentSelected.'>Licensed Real Estate Agent</option>
									<option value="1"'.$assocBrokerSelected.'>Licensed Associate Broker</option>
									<option value="2"'.$brokerSelected.'>Licensed Real Estate Broker</option>
								</select></td>';
						$str.= '</tr>';
						break;
					case 'license' :
						$str.= '<tr>';
						$str.= '<td>License <span class="red">*</span></td>
								<td># <input type="text" name="agent_license_number" value="'.$xUserData['Profile']['agent_license_number'].'" />';
						$str.= ' <select name="agent_license_state">
									<option value="">- License State -</option>';
									$theStates = returnStates();
									foreach($theStates as $key=>$state) {
										$stateSelected = (!empty($xUserData['Profile']['agent_license_state']) && $xUserData['Profile']['agent_license_state']==$key) ? ' selected="SELECTED"' : '';
										$str.= '<option value="'.$key.'"'.$stateSelected.'>'.$state.'</option>';
									}
						$str.= '</select></td>';
						$str.= '</tr>';
						break;
					case 'occupants' :
						$str.= '<tr>';
						$str.= '<td>Occupants/Pets <span class="red">*</span></td>
								<td>';
						$str.= '<select name="tenant_occupants_adults">';
									for($i=1; $i<=8; $i++) {
										$s = ($i==1) ? ' adult' : ' adults';
										$selected = ($i==$xUserData['Profile']['tenant_occupants_adults']) ? ' selected="SELECTED"' : '';
										$str.= '<option value="'.$i.'"'.$selected.'>'.$i.$s.'</option>';	
									}
						$str.= '</select>';
						$str.= '<select name="tenant_occupants_children">';
									for($i=0; $i<=8; $i++) {
										$s = ($i==1) ? ' child' : ' children';
										$selected = ($i==$xUserData['Profile']['tenant_occupants_children']) ? ' selected="SELECTED"' : '';
										$str.= '<option value="'.$i.'"'.$selected.'>'.$i.$s.'</option>';	
									}
						$str.= '</select>';
						$str.= '<span class="or"> and </span>';
						$str.= '<select name="tenant_pets_dogs">';
									for($i=0; $i<=4; $i++) {
										$s = ($i==1) ? ' dog' : ' dogs';
										$selected = ($i==$xUserData['Profile']['tenant_pets_dogs']) ? ' selected="SELECTED"' : '';
										$str.= '<option value="'.$i.'"'.$selected.'>'.$i.$s.'</option>';	
									}
						$str.= '</select>';
						$str.= '<select name="tenant_pets_cats">';
									for($i=0; $i<=4; $i++) {
										$s = ($i==1) ? ' cat' : ' cats';
										$selected = ($i==$xUserData['Profile']['tenant_pets_cats']) ? ' selected="SELECTED"' : '';
										$str.= '<option value="'.$i.'"'.$selected.'>'.$i.$s.'</option>';	
									}
						$str.= '</select>';
						$str.= '<select name="tenant_pets_other">';
									for($i=0; $i<=4; $i++) {
										$s = ($i==1) ? ' other pet' : ' other pets';
										$selected = ($i==$xUserData['Profile']['tenant_pets_other']) ? ' selected="SELECTED"' : '';
										$str.= '<option value="'.$i.'"'.$selected.'>'.$i.$s.'</option>';	
									}
						$str.= '</select>';
						$str.= '</td>';
						$str.= '</tr>';
						break;
						case 'description' :
							$str.= '<tr>';
							$str.= '<td>Description</td>
									<td>
										<textarea rows="10" style="width:90%;" name="description">'.$xUserData['Profile']['description'].'</textarea>
									</td>';
							$str.= '</tr>';
							break;
						case 'looking_for' :
							$str.= '<tr>';
							$str.= '<td>Looking For<span class="red">*</span></td>
									<td>
										$ <input type="text" name="tenant_max_rent" size="7" value="'.$xUserData['Profile']['tenant_max_rent'].'" />
										<span class="or">for a</span>';
										$brSelected0 = ($xUserData['Profile']['tenant_desired_beds']==0) ? ' selected="SELECTED"' : '';
										$brSelected1 = ($xUserData['Profile']['tenant_desired_beds']==1) ? ' selected="SELECTED"' : '';
										$brSelected2 = ($xUserData['Profile']['tenant_desired_beds']==2) ? ' selected="SELECTED"' : '';
										$brSelected3 = ($xUserData['Profile']['tenant_desired_beds']==3) ? ' selected="SELECTED"' : '';
										$brSelected4 = ($xUserData['Profile']['tenant_desired_beds']==4) ? ' selected="SELECTED"' : '';
										$brSelected5 = ($xUserData['Profile']['tenant_desired_beds']==5) ? ' selected="SELECTED"' : '';
										$brSelected6 = ($xUserData['Profile']['tenant_desired_beds']==6) ? ' selected="SELECTED"' : '';
							$str.=	'	<select name="tenant_desired_beds">
											<option value="0"'.$brSelected0.'>Studio</option>
											<option value="1"'.$brSelected1.'>1 BR</option>
											<option value="2"'.$brSelected2.'>2 BR</option>
											<option value="3"'.$brSelected3.'>3 BR</option>
											<option value="4"'.$brSelected4.'>4 BR</option>
											<option value="5"'.$brSelected5.'>5 BR</option>
											<option value="6"'.$brSelected6.'>6+ BR</option>
										</select>';
							$choices = explode(',',$xUserData['Profile']['tenant_desired_housing']);
							$whChecked = (in_array(1, $choices)) ? ' checked="CHECKED"' : '';
							$aChecked = (in_array(2, $choices)) ? ' checked="CHECKED"' : '';
							$str.= '	<input type="checkbox" name="tenant_desired_housing[]" value="1"'.$whChecked.' />Whole House
										<input type="checkbox" name="tenant_desired_housing[]" value="2"'.$aChecked.' />Apartment
									</td>';
							$str.= '</tr>';
							break;
						case 'current_locale' : 
							if ($xUserData['Profile']['current_locale'])
								$locale = explode(", ",$xUserData['Profile']['current_locale']);
							if (isset($locale)) {
								$localeState = (isset($locale[1])) ? $locale[1] : $locale[0];
								$localeCity = (isset($locale[1])) ? $locale[0] : '';
							}
							$str.= '<tr>';
							$str.= '<td>Current Location</td>
									<td>
										<select name="current_locale_state">
											<option value="">Select a state</option>';
										
											$theStates = returnStates();
											foreach($theStates as $key=>$state) {
												$stateSelected = (isset($locale) && $localeState==$key) ? ' selected="SELECTED"' : '';
												$str.= '<option value="'.$key.'"'.$stateSelected.'>'.$state.'</option>';
											}
							$cityDisabled = (isset($locale)) ? '' : ' disabled="DISABLED"';
							$str.= 		'</select>
										<select name="current_locale_city"'.$cityDisabled.'>';
							if (isset($locale)) {
								$str.= performAction('locales','ajax_city',array($localeState,true,$localeCity));
							} else
								$str.= '<option value="">Select a city</option>';
							$str.= '	</select>
									</td></tr>';
							break;
						case 'desired_locale' : 
							$str.= '<tr>';
							$str.= '<td>Desired Locations <span class="red">*</span></td>
									<td><div id="ui-popup-editProfile-clearfix"><select name="desired_locale_state">
											<option value="">Select a state</option>';
										
											$theStates = returnStates();
											foreach($theStates as $key=>$state) {
												$stateSelected = ($key=='NY') ? ' selected="SELECTED"' : '';
												$str.= '<option value="'.$key.'"'.$stateSelected.'>'.$state.'</option>';
												if(!empty($stateSelected))
													$desiredLocaleState = $key;
											}
							$str.= 		'</select>
										<span class="or">&RightArrow;</span>';
							$desiredLocaleDisabled = (!isset($desiredLocaleState)) ? ' disabled="DISABLED"' : '';
							$str.=		'<select name="desired_locale_city"'.$desiredLocaleDisabled.'>';
								if (isset($desiredLocaleState)) {
									$str.= performAction('locales','ajax_city',array($desiredLocaleState,true));
								} else
									$str.= '<option value="">Select a city</option>';
							$str.= '	</select></div>
							<ul id="ui-popup-desired-location-list">';
							foreach($xUserData['Locale'] as $location) {
								$str.= '<li><a href="javascript:void(0)" class="remove_desired_locale" id="'.$location['locales_users']['id'].'">x</a> '.$location['Locale']['city_name'].', '.$location['Locale']['city_state'].'</li>';
							}
							$str.= '	</ul></td>';
							$str.= '</tr>';
							break;
						case 'credit' :
							$mSelected = ($xUserData['Profile']['tenant_credit_snapshot']==1) ? ' selected="SELECTED"' : '';
							$gSelected = ($xUserData['Profile']['tenant_credit_snapshot']==2) ? ' selected="SELECTED"' : '';
							$eSelected = ($xUserData['Profile']['tenant_credit_snapshot']==3) ? ' selected="SELECTED"' : '';
							$str.= '<tr>';
							$str.= '<td>Credit Score <span class="red">*</span></td>
									<td>
										<select name="tenant_credit_snapshot">
											<option value="">Select one</option>
											<option value="1"'.$mSelected.'>Marginal</option>
											<option value="2"'.$gSelected.'>Good</option>
											<option value="3"'.$eSelected.'>Excellent</option>
										</select>
										<span class="or">or</span>
										<input type="text" name="tenant_credit_score" size="4" value="'.$xUserData['Profile']['tenant_credit_score'].'" />
									</td>';
							$str.= '</tr>';
							break;
						case 'email' :
							$verified = ($xUserData['Setting']['verify_email']) ? 'Verified' : 'Not Verified';
							$str.= '<tr>';
							$str.= '<td>Email</td>
									<td>';
							$str.= $xUserData['Profile']['contact_email'].' ';
							$str.= '<input type="text" name="Contact-email" style="display:none;" value="'.$xUserData['Profile']['contact_email'].'" />'.$verified;
							$str.= '<br />(<a href="javascript:void(0)">Change</a>)';
							$str.= '</td>';
							$str.= '</tr>';
							break;
						case 'phone_cell' :
							$str.= '<tr>';
							$str.= '<td>Cell Phone</td>
									<td>
										<input type="text" name="Contact-cell_phone_1" class="ui-input-phone"/>
										<input type="text" name="Contact-cell_phone_2" class="ui-input-phone"/>
										<input type="text" name="Contact-cell_phone_3" class="ui-input-phone"/>
									</td>';
							$str.= '</tr>';
							break;
						case 'phone_home' :
							$str.= '<tr>';
							$str.= '<td>Home Phone</td>
									<td>
										<input type="text" name="Contact-home_phone_1" class="ui-input-phone"/>
										<input type="text" name="Contact-home_phone_2" class="ui-input-phone"/>
										<input type="text" name="Contact-home_phone_3" class="ui-input-phone"/>
									</td>';
							$str.= '</tr>';
							break;
						case 'phone_work' :
							$str.= '<tr>';
							$str.= '<td>Work Phone</td>
									<td>
										<input type="text" name="Contact-work_phone_1" class="ui-input-phone"/>
										<input type="text" name="Contact-work_phone_2" class="ui-input-phone"/>
										<input type="text" name="Contact-work_phone_3" class="ui-input-phone"/>
									</td>';
							$str.= '</tr>';
							break;
						case 'add_employer' :
							$str.= '<tr>';
							$str.= '<td>
										<a href="javascript:void(0)" id="add_employer">Add Employer +</a></td>
									<td id="add_employer_content">
									</td>';
							$str.= '</tr>';
							break;
						case 'add_reference' :
							$str.= '<tr>';
							$str.= '<td>
										<a href="javascript:void(0)" id="add_reference">Add Reference +</a></td>
									<td>
									</td>';
							$str.= '</tr>';
							break;
					}
				}
			$str.= '</table>';
			$str.= '</li>';
			echo $str;
		}
		?>
		</ul>
		
		<div id="ui-popup-submitContainer">
			<input type="submit" name="editProfile-submit" id="ui-popup-editProfile-submit" value="Save Changes" />
		</div>
	</form>
</div>