<?php
class User extends Model {
    var $hasOne = array(
        'Profile'=>'Profile',
        'Setting'=>'Setting'
    );
    var $hasMany = array(
        'Employer'=>'Employer',
        'Reference'=>'Reference',
        'Network'=>'Network',
        'Picture'=>'Picture',
        'Upload'=>'Upload',
        'Session'=>'Session'
    );
    var $hasManyAndBelongsToMany = array(
    	'Locale'=>'Locale',
        'Privilege'=>'Privilege',
        'Review'=>'Review',
        'Request'=>'Request',
        'Template'=>'Template'
    );
    
    var $accountTypes = array(
        'Tenant'    =>  0,
        'Landlord'  =>  1,
        'Agent'     =>  2
    );
	
	function returnName($user_id) {
		$this->id = $user_id;
		$data = $this->search();
		$name = '';
		if ($data['Profile']['first_name'])
			$name.= $data['Profile']['first_name'].' ';
		if ($data['Profile']['middle_name'])
			$name.= $data['Profile']['middle_name'].' ';
		if ($data['Profile']['last_name'])
			$name.= $data['Profile']['last_name'];
		return $name;
	}
	
	function parsePrivilegeUserData($id) {
		$this->id = $id;
		$this->showHasOne();
		$this->showHasMany();
		$this->showHMABTM();
		$data = $this->search();
		return $this->parseUserData($data,array(true,true,false,true,false,true,false,false,false,false));
	}
	function parseUserData($data = array(),$options = array()) {
		// set options
		$option_includeUser 		= isset($options[0]) ? $options[0] : true;
		$option_includeProfile 		= isset($options[1]) ? $options[1] : true;
		$option_includeEmployer		= isset($options[2]) ? $options[2] : false;
		$option_includeLocale		= isset($options[3]) ? $options[3] : false;
		$option_includePrivilege	= isset($options[4]) ? $options[4] : false;
		$option_includePicture		= isset($options[5]) ? $options[5] : false;
		$option_includeReference	= isset($options[6]) ? $options[6] : false;
		$option_includeRequest		= isset($options[7]) ? $options[7] : false;
		$option_includePending		= isset($options[8]) ? $options[8] : false;
		$option_includeUpload		= isset($options[9]) ? $options[9] : false;
		// Set some constants
		$apiData = array();
		$apiData['User'] = array();
			if (isset($data['User']['user_type']) && $data['User']['user_type']==0) {
				if ($option_includeEmployer) $apiData['Employer'] = array();
				if ($option_includeLocale) $apiData['DesiredLocale'] = array();
				if ($option_includePrivilege) $apiData['Agents'] = array();
				if ($option_includeReference) $apiData['References'] = array();
			} else {
				if ($option_includeEmployer) $apiData['Agency'] = array();
				if ($option_includeLocale) $apiData['CoverageLocale'] = array();
				if ($option_includePrivilege) $apiData['Tenants'] = array();
			}
		if ($option_includePicture) $apiData['Picture'] = array();
		if ($option_includeRequest) $apiData['Request'] = array();
		if ($option_includePending) $apiData['Pending'] = array();
		if ($option_includeUpload) $apiData['Document'] = array();

			foreach($data as $db=>$fields) {
				foreach($fields as $k=>$v) {
				/********* USERS DB *********/
					if ($db=='User') {
						if ($k=='id')
							$apiData['User']['tmls_number'] = $v;
						elseif($k=='user_type')
							$apiData['User'][$k] = ($v==1) ? 'Agent' : 'Tenant';
					/********* PROFILES DB *********/
					} elseif($db=='Profile' && $option_includeProfile) {
						// General properties
						if ($k=='first_name' ||
							$k=='middle_name' ||
							$k=='last_name' ||
							$k=='gender' ||
							$k=='dob' ||
							$k=='current_locale' ||
							$k=='contact_email' ||
							$k=='contact_home' ||
							$k=='contact_cell' ||
							$k=='contact_work' ||
							$k=='contact_best_method' ||
							$k=='description') {
								
								$apiData['User'][$k] = $v;
							}
						// If tenant
						if ($apiData['User']['user_type']=='Tenant') {
							if ($k=='tenant_credit_score' ||
								$k=='tenant_credit_snapshot' ||
								$k=='tenant_move_date' ||
								$k=='tenant_min_rent' ||
								$k=='tenant_max_rent' ||
								$k=='tenant_desired_beds' ||
								$k=='tenant_desired_baths' ||
								$k=='tenant_occupants_adults' ||
								$k=='tenant_occupants_children' ||
								$k=='tenant_pets_dogs' ||
								$k=='tenant_pets_cats' ||
								$k=='tenant_pets_other' ||
								$k=='tenant_desired_amenities' ||
								$k=='tenant_desired_housing' ||
								$k=='tenant_on_program' ||
								$k=='tenant_program_years' ||
								$k=='tenant_program_voucher' ||
								$k=='tenant_military_branch') {
									$apiData['User'][$k] = $v;
							}
						// If agent
						} else {
							if ($k=='agent_license_number' ||
								$k=='agent_license_state' ||
								$k=='agent_status' ||
								$k=='agent_agency_name' ||
								$k=='agency_agency_address') {
									$apiData['User'][$k] = $v;
									if ($k=='agent_status') {
										switch($v) {
											case 0 : $apiData['User'][$k] = 'Agent'; break;
											case 1 : $apiData['User'][$k] = 'Associate Broker'; break;
											case 2 : $apiData['User'][$k] = 'Broker'; break;
										}	
									}
							}
						}
					/********* EMPLOYERS DB *********/
					} elseif ($db=='Employer' && $option_includeEmployer) {
						if ($apiData['User']['user_type']=='Tenant') {
							foreach($v as $employer) {
								if (empty($employer['upload_id'])) $employer['employer_picture'] = BASE_PATH.'/images/tmls-default-employer-picture.png';
								else {
									// Handle finding the upload_id and posting it into employer_picture
									$employer['employer_picture'] = null;
								}
								unset($employer['user_id']);
								unset($employer['upload_id']);
								// don't send unnecessary data
								if (empty($employer['start_month'])) unset($employer['start_month']);
								if (empty($employer['start_year'])) unset($employer['start_year']);
								if (empty($employer['end_month'])) unset($employer['end_month']);
								if (empty($employer['end_year'])) unset($employer['end_year']);
								unset($employer['agent_license_status']);
								unset($employer['agent_license_number']);
								array_push($apiData['Employer'], $employer);
							}
						} elseif ($apiData['User']['user_type']=='Agent') {
							foreach($v as $employer) {
								if (empty($employer['upload_id'])) $employer['employer_picture'] = BASE_PATH.'/images/tmls-default-employer-picture.png';
								else {
									// Handle finding the upload_id and posting it into employer_picture

									$employer['employer_picture'] = performAction('employers','getPictureFromId',array($employer['id']));
								}
								unset($employer['user_id']);
								unset($employer['upload_id']);
								unset($employer['pay_amount']);
								unset($employer['pay_period']);
								unset($employer['description']);
								// don't send unnecessary data
								if (empty($employer['start_month'])) unset($employer['start_month']);
								if (empty($employer['start_year'])) unset($employer['start_year']);
								if (empty($employer['end_month'])) unset($employer['end_month']);
								if (empty($employer['end_year'])) unset($employer['end_year']);
								switch($employer['agent_license_status']) {
									case 0 : $employer['agent_license_status'] = 'Agent'; break;
									case 1 : $employer['agent_license_status'] = 'Associate Broker'; break;
									case 2 : $employer['agent_license_status'] = 'Broker'; break;
								}
								array_push($apiData['Agency'], $employer);
							}
						}
					/********* LOCALES DB *********/
					} elseif ($db=='Locale' && $option_includeLocale) {
						//printr($v);
						if ($v['locales_users']['searchable']==1 || $v['locales_users']['agent']==1) {
							unset($v['locales_users']);
							unset($v['Locale']['id']);
							$which = ($apiData['User']['user_type']=='Tenant') ? 'Desired' : 'Coverage';
							array_push($apiData[$which.'Locale'], $v['Locale']);
						}
					/********* PICTURES DB *********/
					} elseif ($db=='Picture' && $option_includePicture) {
						//printr($v);
						$v['Picture']['url'] = BASE_PATH.'/uploads/'.$v['Picture']['user_id'].'/'.$v['Picture']['file_name'].'.jpg';
						$v['Picture']['thmb'] = BASE_PATH.'/uploads/'.$v['Picture']['user_id'].'/'.$v['Picture']['file_name'].'_thumb.jpg';
						$v['Picture']['lthmb'] = BASE_PATH.'/uploads/'.$v['Picture']['user_id'].'/'.$v['Picture']['file_name'].'_lthumb.jpg';
						unset($v['Picture']['user_id']);
						unset($v['Picture']['hidden']);
						array_push($apiData['Picture'], $v['Picture']);
					/********* UPLOADS DB *********/
					} elseif ($db=='Upload' && $option_includeUpload) {
						//printr($v);
						$v['Upload']['url'] = BASE_PATH.'/uploads/'.$v['Upload']['user_id'].'/'.$v['Upload']['file_name'];
						unset($v['Upload']['user_id']);
						unset($v['Upload']['hidden']);
						if($v['Upload']['upload_type']=='employer_picture')
							unset($v['Upload']);
						else
							array_push($apiData['Document'], $v['Upload']);
					/********* REQUESTS DB *********/
					} elseif ($db=='Request') {
						$parsed = false;
						//printr($v);
						if ($option_includeRequest) {
							if ($v['requests_users']['user_id']==$v['Request']['to_id']) {
								$user = $this->parsePrivilegeUserData($v['Request']['from_id']);
								unset($v['requests_users']);
								unset($v['Request']['user_id']);
								$user['Request'] = $v['Request'];
								$v['Request'] = $user;
								array_push($apiData['Request'], $v['Request']);
								$parsed=true;
							}
						}
						if ($option_includePending && !$parsed) {
							if ($v['requests_users']['user_id']==$v['Request']['from_id']) {
								$user = $this->parsePrivilegeUserData($v['Request']['to_id']);
								unset($v['requests_users']);
								unset($v['Request']['user_id']);
								$user['Request'] = $v['Request'];
								$v['Request'] = $user;
								array_push($apiData['Pending'], $v['Request']);
							}
						}
					/********* PRIVILEGES DB *********/
					} elseif ($db=='Privilege' && $option_includePrivilege) {
						//printr($v);
						$which = ($apiData['User']['user_type']=='Tenant') ? 'Agents' : 'Tenants';

						if ($v['privileges_users']['user_id']==$v['Privilege']['from_id'])
							$user = $this->parsePrivilegeUserData($v['Privilege']['to_id']);
						else
							$user = $this->parsePrivilegeUserData($v['Privilege']['from_id']);
						unset($v['privileges_users']);
						$user['Privileges'] = $v['Privilege'];
						$v['Privilege'] = $user;
						array_push($apiData[$which], $v['Privilege']);
					}
				}
			}

		// Clean up
		if (empty($apiData['User']) || !$option_includeUser)
			unset($apiData['User']);

		// Return data
		return ($apiData);
	}

	function registerUser($post = array()) {
		switch($post) {
			case 'name' :
			
		}
	}
}