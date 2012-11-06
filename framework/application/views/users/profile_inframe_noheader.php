		<?php
		echo $html->includeJs('source/tmls.profile');
		#printr($profile);
		/*
		 * BODY
		 */

		/*
		 * PROFILE BUTTONS
		 * A sorted list of the buttons that we can expect on any given profile
		 */
		$buttons = array();
		$buttons = array(
			'Request agency',
			'Send message',
			'Manage agency',
			'Print profile',
			'Add to contacts'
		);

		/*
		 * PROFILE OVERVIEW BUBBLE
		 */
		$profile_overview = '';

		/*
		 * PROFILE FIELDS
		 * A sorted list of the fields and their appropriate headers
		 */
		$fields = array();
		if (isset($profile) && $profile['User']['user_type']=='Agent') {
			$fields = array(
				'Description'=>array(
					'description'
				),
				'Agency'=>array(
					'agency_name',
					'agency_address',
					'agent_status',
					'license'
				),
				'General'=>array(
					'tmls',
					'name'
				),
				'Coverage Area'=>array(
					'coverage_locale'
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
				'Description'=>array(
					'description'
				),
				'General'=>array(
					'tmls',
					'name',
					'occupants',
					'pets',
					'can_afford',
					'desired_beds',
					'move_date',
					'credit',
				),
				'Desired Areas'=>array(
					'desired_locale'
				),
				'Income'=>array(
					'income'
				),
				'References'=>array(
					'references'
				),
				'Contact'=>array(
					'email',
					'phone_cell',
					'phone_home'
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
		/*
		 * FORMAT THE USER
		 */
		// Name
		$name = '';
		if ($profile['User']['first_name'])
			$name.= $profile['User']['first_name'];
		if ($profile['User']['middle_name'])
			$name.= ' '.$profile['User']['middle_name'];
		if ($profile['User']['last_name'])
			$name.= ' '.$profile['User']['last_name'];

		// Picture
		if (count($profile['Picture']) > 0) {
			foreach ($tmls_user['Picture'] as $picture) {
				if ($picture['default'])
					$picture_url = $picture['lthmb'];
			}
		} else {
			$picture_url = BASE_PATH . '/images/ui-default-noPic.png';
		}

		// Best contact method
		$contact_method = '';
		if ($profile['User']['contact_cell']) {
			$contact_method = $profile['User']['contact_cell'];
		} else {
			$contact_method = 'Send a message';
		}
		// Agency number
		$profile_agency_number = formatPhone('6317509225');
		// Profile rating
		$profile_rating = 5;
		$profile_rating_stars = 'N/A';

		// Overview bubble and buttons
		if (isset($profile) && $profile['User']['user_type']=='Agent') {
			// Agent profile
			// Depending on access levels we can dislpay different things
			$profile_overview = <<< END
<b>Licensed Real Estate {$profile['User']['agent_status']}<br />
{$profile['User']['agent_agency_name']}<br />
{$profile_agency_number}</b>

<div class="overview-title">Contact</div>
{$contact_method}

<div class="overview-title">Reviews</div>
{$profile_rating_stars}
END;
		} else {
			// Tenant profile
			// We display different buttons depending on different access levels
						$profile_overview = <<< END
Looking for a

<div class="overview-title">Contact</div>
{$contact_method}
END;
		}
		?>
		<div id="content">
		<div id="tmls-ui-body-content">
			<div id="profile-overview-bubble-container">
				<div id="profile-overview-bubble">
					<?php
						echo $profile_overview;
					?>
				</div>
			</div>
			<?php
				echo '<a href="javascript:void(0)" onclick="open_window(\'/pictures/gallery/'.$profile['User']['tmls_number'].'\',300,200)"><img src="' . $picture_url. '" id="tmls-ui-default-picture" /></a>';
			?>
			<div class="clearfix-both"></div>
		</div>
		<ul id="tmls-ui-body-spaces">
			<?php
			$li_open = false;
			$table_open = false;
			$titleArray = array();
			$str = '';
			$table_req = false;
			$require_tables = array(
				'General',
				'Income',
				'Contact',
				'Other',
				'Agency'
			);
			// access
			$access = true;
		foreach($fields as $title=>$field) {
			// Clean up
			if ($table_req && $li_open) $str.='</table>';
			if ($li_open) $str.='</li>';
			$table_req = false;

			if (!in_array($title,$titleArray)) {
				if (in_array($title,$require_tables))
					$table_req = true;

				$str.= '<li>';
				$str.= ($access) ? '<a href="javascript:void(0)" class="edit" id="'.$title.'"><span class="edit_icon"></span>Edit</a>' : '';
				$str.= '<span class="spaces-title">'.$title.'</span>';
				if ($table_req) $str.='<table class="spaces-table">';
				array_push($titleArray,$title);
				$li_open = true;
			}

			foreach($field as $field) {
				switch($field) {
					case 'description' :
						$description = ($profile['User']['description']) ? $profile['User']['description'] : 'No description available';
						$str.= '<span class="profile-description">'.$description.'</span>';
					break;
					case 'tmls' :
						$str.= '<tr>
							        <td>TMLS#</td><td>'.$profile['User']['tmls_number'].'</td>
							    </tr>';
					break;
					case 'name' :
						$str.= '<tr>
									<td>Full Name</td>
									<td>';
						$name = '';
			    		if ($profile['User']['first_name'])
							$name.= $profile['User']['first_name'].' ';
						if ($profile['User']['middle_name'])
							$name.= $profile['User']['middle_name'].' ';
						if ($profile['User']['last_name'])
							$name.= $profile['User']['last_name'];
						$str.= $name;
						$str.= '</td></tr>';
					break;
					case 'can_afford' :
						$str.= '<tr>
							        <td>Can Afford</td><td>$'.$profile['User']['tenant_max_rent'].'</td>
							    </tr>';
					break;
					case 'desired_beds' :
						$desired_beds = ($profile['User']['tenant_desired_beds']>0) ? $profile['User']['tenant_desired_beds'] : 'Studio';
						$str.= '<tr>
							        <td>Desired Bedrooms</td><td>'.$desired_beds.'</td>
							    </tr>';
					break;
					case 'desired_locale' :
						$str.= '<div id="areamap"></div>';
						$stateTitleLocale = array();
						foreach($profile['DesiredLocale'] as $locale) {
							$stateTitleLocale[$locale['city_state']][$locale['city_zip']] = $locale;
						}
						foreach($stateTitleLocale as $state=>$cities) {
							$str.= '<span class="ui-profile-locale-state-title"><a href="'.BASE_PATH.'/locales/search/'.$state.'">'.returnStates($state).'</a></span><span class="ui-profile-locale-cities">';
							foreach($cities as $city) {
								$str.= '<a href="'.BASE_PATH.'/locales/search/'.$city['city_state'].'-'.$city['city_name'].'-'.$city['city_zip'].'/">'.$city['city_name'].'</a>, ';
							}
							$str = substr($str,0,strlen($str)-2);
							$str.= '</span>';
						}
					break;
					case 'occupants' :
						$str.= '<tr>
							        <td>Occupants</td><td>';
						$str.= ($profile['User']['tenant_occupants_adults']==1) ? $profile['User']['tenant_occupants_adults'].' adult' : $profile['User']['tenant_occupants_adults'].' adults';
						if ($profile['User']['tenant_occupants_children']) {
							if ($profile['User']['tenant_occupants_children']==1)
								$str.= ', '.$profile['User']['tenant_occupants_children'].' child';
							else
								$str.= ', '.$profile['User']['tenant_occupants_children'].' children';
						}
						$str.= '</td>
							    </tr>';
					break;
					case 'pets' :
						$str.= '<tr>
							        <td>Pets</td><td>';
						if ($profile['User']['tenant_pets_dogs']) {
							if ($profile['User']['tenant_pets_dogs']==1)
								$str.= $profile['User']['tenant_pets_dogs'].' dog, ';
							else
								$str.= $profile['User']['tenant_pets_dogs'].' dogs, ';
						}
						if ($profile['User']['tenant_pets_cats']) {
							if ($profile['User']['tenant_pets_cats']==1)
								$str.= $profile['User']['tenant_pets_cats'].' cat, ';
							else
								$str.= $profile['User']['tenant_pets_cats'].' cats, ';
						}
						if ($profile['User']['tenant_pets_other']) {
							if ($profile['User']['tenant_pets_other']==1)
								$str.= $profile['User']['tenant_pets_other'].' other pet, ';
							else
								$str.= $profile['User']['tenant_pets_other'].' other pets, ';
						}
						if ($profile['User']['tenant_pets_dogs'] || $profile['User']['tenant_pets_cats'] || $profile['User']['tenant_pets_other'])
							$str = substr($str,0,strlen($str)-2);
						else
							$str.= 'No pets';
							
						$str.= '</td>
							    </tr>';
					break;
					case 'move_date' :
						$str.= '<tr>
							        <td>Move-in Date</td><td>';
						$str.= ($profile['User']['tenant_move_date']) ? date("F d, Y", strtotime($profile['User']['tenant_move_date'])) : 'N/A';
						$str.= '</td>
							    </tr>';
						break;
					case 'credit' :
						$str.= '<tr>
							        <td>Credit Score</td><td>';
						if ($profile['User']['tenant_credit_score']) {
							$str.= $profile['User']['tenant_credit_score'];
						}
						if ($profile['User']['tenant_credit_snapshot']) {
							$str.= ($profile['User']['tenant_credit_score']) ? ' (' : '';
							switch($profile['User']['tenant_credit_snapshot']) {
								case 1 : $str.= 'Marginal'; break;
								case 2 : $str.= 'Good'; break;
								case 3 : $str.= 'Excellent'; break;
							}
							$str.= ($profile['User']['tenant_credit_score']) ? ') ' : '';
						}
						if (!$profile['User']['tenant_credit_score'] && !$profile['User']['tenant_credit_snapshot'])
							$str.= 'N/A';
						$str.= '</td>
							    </tr>';
					break;
					case 'email' :
						$str.= '<tr>
							        <td>Email</td><td>';
						$str.= ($profile['User']['contact_email']) ? $profile['User']['contact_email'] : 'N/A';
						$str.= '</td>
							    </tr>';
					break;
					case 'phone_cell' : 
						$str.= '<tr>
							        <td>Cell Phone</td><td>';
						$str.= ($profile['User']['contact_cell']) ? formatPhone($profile['User']['contact_cell']) : 'N/A';
						$str.= '</td>
							    </tr>';
					break;
					case 'phone_home' :
						$str.= '<tr>
							        <td>Home Phone</td><td>';
						$str.= ($profile['User']['contact_home']) ? formatPhone($profile['User']['contact_home']) : 'N/A';
						$str.= '</td>
							    </tr>';
					break;
					case 'phone_work' :
						$str.= '<tr>
							        <td>Work Phone</td><td>';
						$str.= ($profile['User']['contact_work']) ? formatPhone($profile['User']['contact_work']) : 'N/A';
						$str.= '</td>
							    </tr>';
					break;
					
					case 'income' :
						$add = ($access) ? '<a href="javascript:void(0)" class="add_employer" id="'.$profile['User']['tmls_number'].'">+ Add new employer</a>' : '';
						foreach($profile['Employer'] as $employer) {
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
						if (count($profile['Employer'])==0) {
						$str.= '<tr>
							        <td>Employers not supplied</td><td>'.$add.'</td>
							    </tr>';
						} else {
							$str.= '<tr>
							        <td></td><td>'.$add.'</td>
							    </tr>';
						}
					break;
					
					case 'references' :
						foreach($profile['References'] as $reference) {
							echo $reference['References']['name'];
						}
						if (count($profile['References'])==0) {
						$str.= '<tr>
							        <td>Names not supplied</td><td></td>
							    </tr>';
						}
					break;
					case 'coverage_locale' :
						$str.= '<div id="areamap"></div>';
					break;

					case 'agency_name' :
						$str.= '<tr>
							        <td>Agency Name</td><td>'.$profile['User']['agent_agency_name'].'</td>
							    </tr>';
					break;
				}
			}
			// Clean up
			if ($table_req && $li_open) $str.='</table>';
			if ($li_open) $str.='</li>';
			$table_req = false;
			
		}
		echo $str;
		?>