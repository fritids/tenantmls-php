<?php
		echo $html->includeJs('source/tmls.profile');
		/*
		 * BODY
		 */
		$agent_tenant_profile = false;
		if ($logged_in) {
			$field = ($tmls_user['User']['user_type']=='Agent') ? 'Tenants' : 'Agents';
			foreach($tmls_user[$field] as $user) {
				if ($user['User']['tmls_number']==$profile['User']['tmls_number'])
					$agent_tenant_profile = true;
			}
		}
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
				'About Me'=>array(
					'description'
				),
				/*
				'General'=>array(
					'tmls',
					'name'
				),
				*/

				'Agency'=>array(
					'agency_name',
					'agency_address',
					'agent_status',
					'license'
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
			$sidebar_fields = array(
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
				'Contact'=>array(
					'email',
					'phone_cell',
					'phone_home'
				)
			);
			// tenant
			$fields = array(
				/*
				'About Me'=>array(
					'description'
				),
				/*
				*/
				'Desired Areas'=>array(
					'desired_locale'
				),
				'Income'=>array(
					'income'
				),
				'References'=>array(
					'references'
				),
				/*
				'Contact'=>array(
					'email',
					'phone_cell',
					'phone_home'
				),
				*/
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
			foreach ($profile['Picture'] as $picture) {
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
		
		// Profile rating
		$profile_rating = 4;
		$profile_rating_stars='<ul id="profile-review-stars">';
		for($i=0;$i<MAX_REVIEW_STARS;$i++) {
			if ($i<$profile_rating)
				$profile_rating_stars.='<li class="gold"></li>';
			else
				$profile_rating_stars.='<li class="default"></li>';
		}
		$profile_rating_stars.='</ul><span id="review-stars-text"><b>92%</b></span><span id="review-stars-links"><a href="#">Write a review</a> - <a href="#">Read all reviews (10)</a></span>';
		// Overview bubble and buttons
		if (isset($profile) && $profile['User']['user_type']=='Agent') {
			// Agent profile
			// Depending on access levels we can dislpay different things
			foreach($profile['Agency'] as $agency) {
				$profile_agency_number = formatPhone($agency['employer_number']);
				$profile_overview = <<< END
{$profile_rating_stars}
<div class="clearfix-left"></div>
<div class="overview-title">Contact</div>
{$contact_method}
END;
			}
		} else {
			$total_occupants = ($profile['User']['tenant_occupants_adults'] + $profile['User']['tenant_occupants_children']>0) ?
				$profile['User']['tenant_occupants_adults'] + $profile['User']['tenant_occupants_children'].' occupants' : '1 occupant';
			$profile_looking_for = '<b>$'.$profile['User']['tenant_max_rent'].'</b>';
			$profile_looking_for.= ' - <b>'.$total_occupants.'</b>';
			$profile_looking_for.= ' - Looking for a <b>';
			if ($profile['User']['tenant_desired_beds']>0) {
				$profile_looking_for.= $profile['User']['tenant_desired_beds'].'br';
			} else {
				$profile_looking_for.= 'studio';
			}
			$profile_looking_for.= '</b>';
			$dh = (!empty($profile['User']['tenant_desired_housing'])) ? explode(',',$profile['User']['tenant_desired_housing']) : array();
			if (count($dh) > 0) {
				foreach($dh as $d) {
					if ($d==0)
						$profile_looking_for.= ' apartment,';
					elseif($d==1)
						$profile_looking_for.= ' whole house,';
				}
				$profile_looking_for = substr($profile_looking_for,0,strlen($profile_looking_for)-1);
			} elseif ($profile['User']['tenant_desired_beds']==0) {	
				$profile_looking_for.= '<b> apartment</b>';
			}
			$profile_looking_for.= ' in';
			foreach($profile['DesiredLocale'] as $dl) {
				$profile_looking_for.= ' <a href="'.BASE_PATH.'/locales/search/">'.$dl['city_name'].', '.$dl['city_state'].'</a>';
			}
			// Tenant profile
			// We display different buttons depending on different access levels
						$profile_overview = <<< END
<div class="overview-title">Income</div>
None

<div class="overview-title">References</div>
None
END;
		}
		?>

		<div id="tmls-ui-body-content">
			<div id="profile-overview-bubble-container">

			<h2 id="profile-name"><?php echo $name; ?></h2>
			<h4 id="profile-subtext"><?php echo ($profile['User']['user_type']=='Tenant') ? $profile_looking_for : 'Licensed Real Estate Agent'; ?></h4>
			<div id="profile-buttons">
				<ul>
					<?php
					if ($agent_tenant_profile) {
						$onclick = 'onclick="open_menu(\'/users/menu/'.$profile['User']['tmls_number'].'\',\'menu\')"';
						$link_text = 'Manage '.$profile['User']['user_type'];
					} else {
						$onclick = 'onclick="open_menu(\'/users/request/'.$profile['User']['tmls_number'].'\',\'menu\')"';
						$link_text = 'Add '.$profile['User']['user_type'];
					}
					echo '<li><a class="button" '.$onclick.' href="javascript:void(0)" id="addUser"><b>'.$link_text.'</b></a><div id="menu-dialog"></div></li>';
					?>
					<li><a class="button" href="#">Send Message</a></li>
				</ul>
				<div class="clearfix-left"></div>
			</div>
				<div id="profile-overview-bubble">
					<?php
						echo $profile_overview;
					?>
				</div>

			</div>
			
			<?php echo '<a href="javascript:void(0)" onclick="open_window(\'/pictures/gallery/'.$profile['User']['tmls_number'].'\',300,200)"><img src="' . $picture_url. '" id="tmls-ui-default-picture" /></a>'; ?>

		</div>
			<?php
				$li_open = false;
				$table_open = false;
				$titleArray = array();
				$str = '<div id="profile-information-sidebar"><ul>';
				// access
				$access = false;
				foreach($sidebar_fields as $title=>$field) {
					// Clean up
					if ($li_open) $str.='</table>';
					if ($li_open) $str.='</li>';

					if (!in_array($title,$titleArray)) {
						$title_id = strtolower(str_replace(' ','',$title));
						$str.= '<li>';
						$str.= ($access) ? '<a href="javascript:void(0)" class="edit" id="'.$title_id.'"><span class="edit_icon"></span>Edit</a>' : '';
						$str.= '<span class="sidebar-title">'.$title.'</span>';
						$str.='<table class="sidebar-table">';
						array_push($titleArray,$title);
						$li_open = true;
					}

					foreach($field as $field) {
						switch($field) {
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
									        <td>Beds</td><td>'.$desired_beds.'</td>
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
									        <td>Move-in</td><td>';
								$str.= ($profile['User']['tenant_move_date']) ? date("F d, Y", strtotime($profile['User']['tenant_move_date'])) : 'N/A';
								$str.= '</td>
									    </tr>';
								break;
							case 'credit' :
								$str.= '<tr>
									        <td>Credit</td><td>';
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
									        <td>Cell</td><td>';
								$str.= ($profile['User']['contact_cell']) ? formatPhone($profile['User']['contact_cell']) : 'N/A';
								$str.= '</td>
									    </tr>';
							break;
							case 'phone_home' :
								$str.= '<tr>
									        <td>Home</td><td>';
								$str.= ($profile['User']['contact_home']) ? formatPhone($profile['User']['contact_home']) : 'N/A';
								$str.= '</td>
									    </tr>';
							break;
							case 'phone_work' :
								$str.= '<tr>
									        <td>Work</td><td>';
								$str.= ($profile['User']['contact_work']) ? formatPhone($profile['User']['contact_work']) : 'N/A';
								$str.= '</td>
									    </tr>';
							break;
						}
					}

						// Clean up
				if ($li_open) $str.='</table>';
				if ($li_open) $str.='</li>';
				}	
				$str.= '</ul>';
			$str.= '</div>';

			$description = ($profile['User']['description']) ? str_replace("\n",'<br />',$profile['User']['description']) : 'No description available';
			$str.= '<span class="profile-title">About Me</span>';
			$str.= '<span class="profile-description">'.$description.'</span>';

			echo $str;
		?>
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
				'References',
				'Other'
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
				$title_id = strtolower(str_replace(' ','',$title));
				$str.= '<li>';
				$str.= ($access) ? '<a href="javascript:void(0)" class="edit" id="'.$title_id.'"><span class="edit_icon"></span>Edit</a>' : '';
				$str.= '<span class="spaces-title">'.$title.'</span>';
				if ($table_req) $str.='<table class="spaces-table">';
				array_push($titleArray,$title);
				$li_open = true;
			}

			foreach($field as $field) {
				switch($field) {
					case 'description' :
						$description = ($profile['User']['description']) ? str_replace("\n",'<br />',$profile['User']['description']) : 'No description available';
						$str.= '<span class="profile-description">'.$description.'</span>';
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
						foreach($profile['Agency'] as $agency) {
							$str.= '<div class="employer-container">';
							$str.= '<span class="employer-picture"><img src="'.$agency['employer_picture'].'" /></span>';
							$str.= '<table class="employer">';

							$str.= '<tr><td>';
							$str.= '<b>'.$agency['company_name'].'</b>';
							$str.= '</td></tr>';
						    $str.= '<tr><td>';
							$str.= 'Licensed '.$agency['agent_license_status'].' <span class="license_number">(#'.$agency['agent_license_number'].')</span>';
							$str.= '</td></tr>';
							$str.= '<tr><td>';
							$str.= $agency['company_locale'];
							$str.= '</td></tr>';
							$str.= '<tr><td>';
							$str.= 'Office: '.formatPhone($agency['employer_number']);
							$str.= '</td></tr>';
							$str.= '</table>';
							$str.= '</div>';
						}
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
<?php #printr($profile); ?>