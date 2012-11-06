<?php
class ProfilesController extends Controller {
    function create_entry($user_type = null, $name = null,$input1 = null,$input2 = null,$input3 = null,$input4 = null,$input5 = null,$input6 = null,$input7 = null) {
    	$inputNameArray = explode(' ',$name);
		if (isset($inputNameArray[0]))
			$this->Profile->first_name = $inputNameArray[0];
		if (count($inputNameArray)>2) {
			$this->Profile->middle_name = $inputNameArray[1];
			$this->Profile->last_name = $inputNameArray[2];
		} elseif (isset($inputNameArray[1])) {
			$this->Profile->last_name = $inputNameArray[1];
		}
		
        $this->Profile->contact_email = $input1;
		
        if($user_type) {
        	// If Agent
			$this->Profile->agent_agency_name = $input2;
			$this->Profile->agent_status = $input3;
		} else {
			$this->Profile->tenant_max_rent = $input2;
			$this->Profile->tenant_desired_beds = $input3;
			$this->Profile->tenant_occupants_adults = $input4;
			$this->Profile->tenant_credit_snapshot = $input5;
		}
        return $this->Profile->save(true);
    }
    
    function beforeAction() {
    }
    
    function afterAction() {
        
    }
	
    function view($user_id = null) {
 		$this->doNotRenderHeader = 1;
			if ($user_id)
				$data[0] = performAction('users', 'fetch', array($user_id));
			$session = (isset($_COOKIE['tmls_uniq_sess'])) ? $_COOKIE['tmls_uniq_sess'] : '';
			if ($checkData = performAction('sessions', 'check', array($session))) {
				$xUserData = performAction('users', 'fetch', array($checkData['User']['id']));
				$this -> set('xUserData',$xUserData);
			}
			$this->set('data',$data[0]);
		
    }
	function update() {
		if (isset($_POST['user_id'])) {
			$profile_id = performAction('users','returnProfileId',array($_POST['user_id']));
			$this->doNotRenderHeader = 1;

			$this->Profile->id = $profile_id;

			// --------- Description  --------- //
			if ($_POST['field']=='aboutme') {
				if ($_POST['description']) {
					$this->Profile->description = $_POST['description'];
				} elseif (!$_POST['description']) {
					$this->Profile->description = 'null';
				}
				echo 1;
			}
			// --------- Name  --------- //
			elseif ($_POST['field']=='name') {
				$inputNameArray = explode(' ',$_POST['name']);
				if (isset($inputNameArray[0]))
					$this->Profile->first_name = $inputNameArray[0];
				if (count($inputNameArray)>2) {
					$this->Profile->middle_name = $inputNameArray[1];
					$this->Profile->last_name = $inputNameArray[2];
				} elseif (isset($inputNameArray[1])) {
					$this->Profile->middle_name = 'null';
					$this->Profile->last_name = $inputNameArray[1];
				}
				echo $js.$field.$_POST['name'];
			} elseif($_POST['field']=='can_afford') {
				$this->Profile->tenant_max_rent = $_POST['tenant_max_rent'];
				echo $js.$field.'$'.$_POST['tenant_max_rent'];
			} elseif($_POST['field']=='desired_beds') {
				$this->Profile->tenant_desired_beds = $_POST['tenant_desired_beds'];
				switch($_POST['tenant_desired_beds']) {
					case 0 : $echo = 'Studio'; break;
					default: $echo = $_POST['tenant_desired_beds'];
				}
				echo $js.$field.$echo;
			} elseif ($_POST['field']=='desired_locale') {
				
			} elseif($_POST['field']=='occupants') {
				$this->Profile->tenant_occupants_adults = $_POST['tenant_occupants_adults'];
				$this->Profile->tenant_occupants_children = $_POST['tenant_occupants_children'];
				$echo = ($_POST['tenant_occupants_adults']==1) ? $_POST['tenant_occupants_adults'].' adult' : $_POST['tenant_occupants_adults'].' adults';
				if ($_POST['tenant_occupants_children']>0) {
					if ($_POST['tenant_occupants_children']==1)
						$echo.= ', '.$_POST['tenant_occupants_children'].' child';
					else
						$echo.= ', '.$_POST['tenant_occupants_children'].' children';
				}
				echo $js.$field.$echo;
			} elseif ($_POST['field']=='pets') {
				$this->Profile->tenant_pets_dogs = $_POST['tenant_pets_dogs'];
				$this->Profile->tenant_pets_cats = $_POST['tenant_pets_cats'];
				$this->Profile->tenant_pets_other = $_POST['tenant_pets_other'];
				$echo = '';
				if ($_POST['tenant_pets_dogs']) {
					if ($_POST['tenant_pets_dogs']==1)
						$echo.= $_POST['tenant_pets_dogs'].' dog, ';
					else
						$echo.= $_POST['tenant_pets_dogs'].' dogs, ';
				}
				if ($_POST['tenant_pets_cats']) {
					if ($_POST['tenant_pets_cats']==1)
						$echo.= $_POST['tenant_pets_cats'].' cat, ';
					else
						$echo.= $_POST['tenant_pets_cats'].' cats, ';
				}
				if ($_POST['tenant_pets_other']) {
					if ($_POST['tenant_pets_other']==1)
						$echo.= $_POST['tenant_pets_other'].' other pet, ';
					else
						$echo.= $_POST['tenant_pets_other'].' other pets, ';
				}
				if ($_POST['tenant_pets_dogs'] || $_POST['tenant_pets_cats'] || $_POST['tenant_pets_other'])
					$echo = substr($echo,0,strlen($echo)-2);
				else
					$echo.= 'No pets';
				
				echo $js.$field.$echo;
			}
			$this->Profile->save();
		}
	}
	function edit() {
		$this->doNotRenderHeader = 1;
		$id = (isset($_POST['user_id'])) ? $_POST['user_id'] : null;
		// will need to check logged in and privilege here
		#$this->set('xUserData', $data = performAction('users', 'fetch', array($user_id)));
		if(isset($_POST['user_id']) && isset($_POST['edit'])) {
			// Get profile by TMLS_NUMBER or if empty get by session data
			$session = (isset($_COOKIE['tmls_uniq_sess'])) ? $_COOKIE['tmls_uniq_sess'] : '';
			if ((!empty($id) && is_numeric(substr($id, 1, strlen($id) - 1))) || ((empty($id) || !is_numeric($id)) && $data = performAction('sessions', 'check', array($session)))) {
				$id = ((!empty($id) && is_numeric(substr($id, 1, strlen($id) - 1)))) ? $id : $data['User']['id'];
				$requestArray = array(
					'API_URL'=> BASE_PATH.'/api/select/'.$id,
					'API_ID'=>API_ID,
					'API_SECRET'=>API_SECRET,
					'USER_SESSION'=>$session,
					'RETURN_TYPE'=>'json'
				);
				$request = new RestRequest($requestArray['API_URL'],'POST',$requestArray);
				$request->execute();
				$json_apidata = json_decode($request->getResponseBody(),true);
				$this -> set('profile', $json_apidata);
			} else {
				header("HTTP/1.0 404 Not Found");
			}

			$this->set('controllerField',strtolower($_POST['edit']));
			$this->set('controllerUserId',$_POST['user_id']);
		} else {
			$this->render = 0;
		}
	}
	function edit_view($user_id = null) {
		$this->doNotRenderHeader = 1;
		if (isset($_COOKIE['tmls_uniq_sess'])) {
			if ($user_id)
				$xUserData[0] = performAction('users', 'fetch', array($user_id));
			else
				$xUserData = performAction('users','check_user',array($_COOKIE['tmls_uniq_sess']));
			$this->set('xUserData',$xUserData[0]);
			
			
		} else {
			return -1;
		}
	}
	/* 
	 * Faux function
	 */
	 function register() {
	 	// LOL I DO NOTHING!
	 }
}
?>
