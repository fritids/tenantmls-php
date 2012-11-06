<?php
class ApiController extends Controller {
	function beforeAction(){
		$this->doNotRenderHeader = 1;
	}
	function afterAction() {
		
	}
	/*=========================== API CALLS ===========================
	 * oauth/[version_number=1.0]
	 * 		Authenticates and restricts an application to use the TMLS API service
	 * 
	 * select/[tmls_number]
	 * 		Selects a user by TMLS# and returns a filtered array
	 * 
	 * update/[tmls_number]
	 * 		Accepts POST - will take any parameter posted to it and update accordingly
	 */
	function oauth($version = '1.0') {
		
	}
	function register() {
		$this->render = 0;
		// Default to json return
		$content_type = 'application/json';
		
		if($_SERVER['REQUEST_METHOD']=='POST') {
			if (isset($_POST['RETURN_TYPE'])) {
				switch($_POST['RETURN_TYPE']) {
					default : case 'json' :
						$content_type = 'application/json';
						break;
					case 'xml' :
						$content_type = 'text/xml';
						break;
				}
			}
			// Set appropriate header
			header('Content-type: '.$content_type);
			
			if (isset($_POST['API_ID']) && isset($_POST['API_SECRET']) && $_POST['API_ID']==API_ID && $_POST['API_SECRET']==API_SECRET) {
				// process our input
				$userController = new UsersController('users','register');
				$profileController = new ProfilesController('profiles','register');
				$settingController = new SettingsController('settings','register');
				$privilegeController = new PrivilegesController('privileges','register');
				$localeController = new LocalesController('locales','register');
				
				// Setup the user account with no email or password
				$userController->User->user_type = $_POST['INPUT_USER_TYPE'];
				$id = $userController->User->save(true);
				
				// Setup the profile
				$profileController->Profile->user_id = $id;
				
				// Setup the settings
				$settingController->Setting->user_id = $id;
				$setting_id = $settingController->Setting->save(true);
				
				/*
				 * Process the information
				 */
				if (isset($_POST['INPUT_NAME'])) {
					$inputNameArray = explode(' ',$_POST['INPUT_NAME']);
					if (isset($inputNameArray[0]))
						$profileController->Profile->first_name = $inputNameArray[0];
					if (count($inputNameArray)>2) {
						$profileController->Profile->middle_name = $inputNameArray[1];
						$profileController->Profile->last_name = $inputNameArray[2];
					} elseif (isset($inputNameArray[1])) {
						$profileController->Profile->last_name = $inputNameArray[1];
					}
				}	
				if (isset($_POST['INPUT_EMAIL_PHONE'])) {
					if (is_numeric($_POST['INPUT_EMAIL_PHONE']))
						$profileController->Profile->contact_cell = $_POST['INPUT_EMAIL_PHONE'];
					else
						$profileController->Profile->contact_email = $_POST['INPUT_EMAIL_PHONE'];
				}
				if (isset($_POST['INPUT_BEDROOMS'])) {
					$profileController->Profile->tenant_desired_beds = $_POST['INPUT_BEDROOMS'];
				}		
				if (isset($_POST['INPUT_RENT'])) {
					$profileController->Profile->tenant_max_rent = $_POST['INPUT_RENT'];
				}	
				if (isset($_POST['INPUT_CREDIT'])) {
					$profileController->Profile->tenant_credit_snapshot = $_POST['INPUT_CREDIT'];
				}
				if (isset($_POST['INPUT_PROGRAM'])) {
					$profileController->Profile->tenant_on_program = ($_POST['INPUT_PROGRAM']) ? 1: 0;
					$profileController->Profile->tenant_program = ($_POST['INPUT_PROGRAM']) ? $_POST['INPUT_PROGRAM'] : null;
				}
				// Save the profile
				$profile_id = $profileController->Profile->save(true);
				
				/*
				 * Insert the locale
				 */
				$locale = explode(', ',$_POST['INPUT_LOCALE']);
				$localeController->Locale->where('city_state',$locale[1]);
				$localeController->Locale->where('city_name',$locale[0]);
				$localeController->Locale->setLimit(1);
				$data = $localeController->Locale->search();
				$locale_id = $data[0]['Locale']['id'];
				// Save the locale
				$localeController->Locale->custom("INSERT IGNORE INTO `locales_users` (`user_id`,`locale_id`) VALUES ('$id','$locale_id')", true);
				
				/*
				 * Create a new privilege for the user
				 * It's messy but it works
				 *  - Create two entries, one for each of the users
				 *  - Anytime one is changed, be sure to change the other
				 *  - Whatever, it works and it's late
				 */
				$tmls = $_POST['INPUT_TMLS'];
				$privilegeController->Privilege->user_id = $tmls;
				$privilegeController->Privilege->view = 1;
				$privilegeController->Privilege->edit = 1;
				$privilegeController->Privilege->upload = 1;
				$privilegeController->Privilege->exclusive = 0;
				$privilegeController->Privilege->logged = date("Y-m-d H:i:s");
				$privilege_id = $privilegeController->Privilege->save(true);
				$privilegeController->Privilege->custom("INSERT INTO `privileges_users` (`user_id`,`privilege_id`) VALUES ('$id','$privilege_id')");
				
				$privilegeController->Privilege->user_id = $id;
				$privilegeController->Privilege->view = 1;
				$privilegeController->Privilege->edit = 1;
				$privilegeController->Privilege->upload = 1;
				$privilegeController->Privilege->exclusive = 0;
				$privilegeController->Privilege->logged = date("Y-m-d H:i:s");
				$privilege_id = $privilegeController->Privilege->save(true);
				$privilegeController->Privilege->custom("INSERT INTO `privileges_users` (`user_id`,`privilege_id`) VALUES ('$tmls','$privilege_id')");
				//make the locale searchable
				$privilegeController->Privilege->custom("UPDATE `locales_users` SET `searchable`=1 WHERE `user_id`='$id'");
				
				// Update the userController
				$userController->User->id = $id;
				$userController->User->profile_id = $profile_id;
				$userController->User->setting_id = $setting_id;
				$userController->User->save();
				
				if ($content_type=='application/json')
					echo json_encode(array('success','TMLS #'.$id.' was successfully created'));
				else
					echo xml_encode(array('success','TMLS #'.$id.' was successfully created'));
				
			} else {
				if ($content_type=='application/json')
					echo json_encode(array('error','API_ID and API_SECRET incorrect or not supplied'));
				else
					echo xml_encode(array('error','API_ID and API_SECRET incorrect or not supplied'));
			}
		} else {
			if ($content_type=='application/json')
				echo json_encode(array('error','The requested page is only accesible through POST'));	
			else
				echo xml_encode(array('error','The requested page is only accesible through POST'));
		}
	}
	
	function select($tmls = null, $field = null) {
		// Disable HTML output
		$this->render = 0;
		// Default to json return
		$content_type = 'application/json';
		
		if($_SERVER['REQUEST_METHOD']=='POST') {
			if (isset($_POST['RETURN_TYPE'])) {
				switch($_POST['RETURN_TYPE']) {
					default : case 'json' :
						$content_type = 'application/json';
						break;
					case 'xml' :
						$content_type = 'text/xml';
						break;
				}
			}
			// Set appropriate header
			header('Content-type: '.$content_type);
			
			if (isset($_POST['API_ID']) && isset($_POST['API_SECRET']) && $_POST['API_ID']==API_ID && $_POST['API_SECRET']==API_SECRET) {
				
				$options = array();
				(isset($_POST['OPTION_INCLUDE_USER'])) ? array_push($options, $_POST['OPTION_INCLUDE_USER']) : array_push($options, false);
				(isset($_POST['OPTION_INCLUDE_PROFILE'])) ? array_push($options, $_POST['OPTION_INCLUDE_PROFILE']) : array_push($options, false);
				(isset($_POST['OPTION_INCLUDE_EMPLOYER'])) ? array_push($options, $_POST['OPTION_INCLUDE_EMPLOYER']) : array_push($options, false);
				(isset($_POST['OPTION_INCLUDE_LOCALE'])) ? array_push($options, $_POST['OPTION_INCLUDE_LOCALE']) : array_push($options, false);
				(isset($_POST['OPTION_INCLUDE_PRIVILEGE'])) ? array_push($options, $_POST['OPTION_INCLUDE_PRIVILEGE']) : array_push($options, false);
				(isset($_POST['OPTION_INCLUDE_PICTURE'])) ? array_push($options, $_POST['OPTION_INCLUDE_PICTURE']) : array_push($options, false);
				(isset($_POST['OPTION_INCLUDE_REFERENCE'])) ? array_push($options, $_POST['OPTION_INCLUDE_REFERENCE']) : array_push($options, false);
				(isset($_POST['OPTION_INCLUDE_REQUEST'])) ? array_push($options, $_POST['OPTION_INCLUDE_REQUEST']) : array_push($options, false);
				(isset($_POST['OPTION_INCLUDE_PENDING'])) ? array_push($options, $_POST['OPTION_INCLUDE_PENDING']) : array_push($options, false);
				(isset($_POST['OPTION_INCLUDE_UPLOAD'])) ? array_push($options, $_POST['OPTION_INCLUDE_UPLOAD']) : array_push($options, false);

				switch($field) {
					case 'income'		: $options[2] = true; break;
					case 'pictures'		: $options[0]=$options[1]=$options[5]=true; break;
					case 'requests' 	: $options[7]=true; break;
					case 'pending' 		: $options[8]=true; break;
					case 'all' 			:
						// Set all to true
						foreach($options as $option=>$value) {
							$options[$option] = true;
						}
					break;
					default				: $options[0]=$options[1]=$options[2]=$options[3]=$options[4]=$options[5]=$options[6]=$options[9]=true; break;
					case 'custom' 		: break;
					#case 'pictures'	: $options[3]=$options[4]=false; break;
				}

				$performAction = performAction('users','api_select',array($tmls,$options));
				if (!$performAction) {
					header("HTTP/1.0 404 Not Found");
					$error = array();
					if (empty($tmls))
						$error['error'] = 'TMLS # not provided';
					else
						$error['error'] = 'Could not locate TMLS #'.$tmls;
					echo ($content_type=='application/json') ? json_encode($error) : xml_encode($error);
				} else {
					// Process any other parameters left
					if ($field) {
						switch($field) {
							case 'profile' : 
								$returnData = (isset($performAction['User'])) ? array(ucfirst($field)=>$performAction['User']) : $performAction;
								break;
							case 'income' : 
								$returnData = (isset($performAction['Employer'])) ? array(ucfirst($field)=>$performAction['Employer']) : $performAction;
								break;
							case 'tenants' : 
								$returnData = (isset($performAction['Tenants'])) ? array(ucfirst($field)=>$performAction['Tenants']) : $performAction;
								break;
							case 'default' : 
								if (isset($performAction['Picture'])) {
									$defaultSet = false;
									foreach($performAction['Picture'] as $pic) {
										if ($pic['default']) {
											$defaultSet = true;
											$returnData = array('Picture'=>$pic);
										}
									}
								} else
									$returnData = $performAction;
								break;
							default : $returnData = (isset($performAction['User'][$field])) ? array($field=>$performAction['User'][$field]) : $performAction;
						}
						
					} else
						$returnData = $performAction;
					
					if ($content_type=='application/json')
						echo json_encode($returnData);
					else {
						echo xml_encode($returnData);
					}
				}
			} else {
				if ($content_type=='application/json')
					echo json_encode(array('error','API_ID and API_SECRET incorrect or not supplied'));
				else
					echo xml_encode(array('error','API_ID and API_SECRET incorrect or not supplied'));
			}
		} else {
			if ($content_type=='application/json')
				echo json_encode(array('error','The requested page is only accesible through POST'));	
			else
				echo xml_encode(array('error','The requested page is only accesible through POST'));
		}
	}
	
	function update($tmls = null, $field = null) {
		// Disable HTML output
		$this->render = 0;
		// Default to json return
		$content_type = 'application/json';
		
		if($_SERVER['REQUEST_METHOD']=='POST') {
			if (isset($_POST['RETURN_TYPE'])) {
				switch($_POST['RETURN_TYPE']) {
					default : case 'json' :
						$content_type = 'application/json';
						break;
					case 'xml' :
						$content_type = 'text/xml';
						break;
				}
			}
			// Set appropriate header
			header('Content-type: '.$content_type);
			// Post the information according to what we have
		} else {
			if ($content_type=='application/json')
				echo json_encode(array('error','The requested page is only accesible through POST'));	
			else
				echo xml_encode(array('error','The requested page is only accesible through POST'));
		}
	}
	
	function run($function = null,$query = null,$field=null) {
		$this->set('runFunction',$function);
		$this->set('runQuery',$query);
		$this->set('runField',$field);
		$session = (isset($_COOKIE['tmls_uniq_sess'])) ? $_COOKIE['tmls_uniq_sess'] : '';
			if ($checkData = performAction('sessions', 'check', array($session))) {
				$xUserData = performAction('users', 'fetch', array($checkData['User']['id']));
				$this -> set('xUserData',$xUserData);
			}
	}

	function plugins($plugin = null,$params = null) {
		// We have to eventually check with OAuth but for now we'll
		// just check with our crummy db
		
		$this->render = 1;
		$defaultParams = array(
			'HEADER'=>1,
			'WIDTH'=>500,
			'HEIGHT'=>300,
			'JAVASCRIPT'=>1,
			'MIN_WIDTH'=>null,
			'MIN_HEIGHT'=>null,
			'TMLS'=>null,
			'AGENT_HEADER'=>1
		);
		switch($plugin) {
			case 'register' :
				$defaultParams['TITLE'] = 'Contact Me';
				break;
			case 'tenantfinder' :
				$defaultParams['RESULTS'] = 'list';
				$session = (isset($_COOKIE['tmls_uniq_sess'])) ? $_COOKIE['tmls_uniq_sess'] : '';
				if ($checkData = performAction('sessions', 'check', array($session))) {
					$this->set('xUserTmls',$checkData['User']['id']);
				}
				break;
		}
		if ($params) {
			$parameters = explode(',',$params);
			$params = array();
			foreach($parameters as $param) {
				$propertyValue = explode('=',$param);
				$params[$propertyValue[0]] = $propertyValue[1];
				foreach($defaultParams as $property=>$value) {
					if ($property==strtoupper($propertyValue[0]))
						$defaultParams[$property] = $propertyValue[1];
					if (strtoupper($propertyValue[0])=='TMLS') {
						$requestArray = array(
							'API_URL'=> BASE_PATH.'/api/select/'.$propertyValue[1],
							'API_ID'=>API_ID,
							'API_SECRET'=>API_SECRET,
							'RETURN_TYPE'=>'json'
						);
						$request = new RestRequest($requestArray['API_URL'],'POST',$requestArray);
						$request->execute();
						$user = json_decode($request->getResponseBody(),true);
						$this->set('data',$user);
					}
				}
			}
		}

		$this->set('plugin',$plugin);
		$this->set('params',$defaultParams);
		if ($defaultParams['TMLS'])
			$this->set('tmls',$defaultParams['TMLS']);
	}
	function examples() {
		$this->doNotRenderHeader = 1;
	}
}
?>
