<?php
class LocalesController extends Controller {
	function beforeAction() {
		$session = (isset($_COOKIE['tmls_uniq_sess'])) ? $_COOKIE['tmls_uniq_sess'] : null;
		if ($data = performAction('sessions', 'check', array($session))) {
			// Get the signed in user information
			$id = $data['Session']['user_id'];
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


			$this->set('logged_in',true);
			$this->set('tmls_user', $json_apidata);
		} else {
			$this->set('logged_in',false);
		}
	}

	function afterAction() {

	}

	function ajax_county() {
		$this -> render = 0;
		$this -> doNotRenderHeader = 1;
		if (isset($_POST['state']))
			$this -> Locale -> where('city_state', $_POST['state']);
		elseif (isset($_POST['city']))
			$this -> Locale -> where('city_name', $_POST['city']);

		$this -> Locale -> orderBy('city_county', 'ASC');
		$this -> Locale -> groupBy('city_county');
		$data = $this -> Locale -> search();
		echo '<option value="">- Select a county -</option>';
		foreach ($data as $result) {
			if ($result['Locale']['city_name'] == $_POST['city'])
				echo '<option value="' . $result['Locale']['city_county'] . '" selected="SELECTED">' . $result['Locale']['city_county'] . ' County</option>';
			else
				echo '<option value="' . $result['Locale']['city_county'] . '">' . $result['Locale']['city_county'] . ' County</option>';
		}
	}

	function ajax_city($state = null, $return = false, $selected_city = null) {
		$this -> doNotRenderHeader = 1;
		if (isset($_POST['state']))
			$this -> Locale -> where('city_state', $_POST['state']);
		elseif (isset($_POST['county']))
			$this -> Locale -> where('city_county', $_POST['county']);
		elseif (isset($state))
			$this -> Locale -> where('city_state', $state);

		$this -> Locale -> orderBy('city_name', 'ASC');
		$this -> Locale -> groupBy('city_name');
		$data = $this -> Locale -> search();
		if (!$return) {
			$str = '';
			$str .= '<option value="">Select a city</option>';
			foreach ($data as $result) {
				if ($selected_city != $result['Locale']['city_zip'])
					$str .= '<option value="' . $result['Locale']['city_zip'] . '">' . $result['Locale']['city_name'] . '</option>';
				else
					$str .= '<option value="' . $result['Locale']['city_zip'] . '" selected="SELECTED">' . $result['Locale']['city_name'] . '</option>';
			}
			return $str;
		} elseif ($return=='json') {
			$return = array();
			$return[0] = 'Select a city';
			foreach ($data as $result) {
				$return[$result['Locale']['city_zip']] = $result['Locale']['city_name'];
			}
			echo json_encode($return);
		} elseif ($return=='php') {
			$return = array();
			$return[0] = 'Select a city';
			foreach ($data as $result) {
				$return[$result['Locale']['city_zip']] = $result['Locale']['city_name'];
			}
			return $return;
		} elseif ($return=='option') {
			$str = '';
			$str .= '<option value="">Select a city</option>';
			foreach ($data as $result) {
				if ($selected_city != $result['Locale']['city_zip'])
					$str .= '<option value="' . $result['Locale']['city_zip'] . '">' . $result['Locale']['city_name'] . '</option>';
				else
					$str .= '<option value="' . $result['Locale']['city_zip'] . '" selected="SELECTED">' . $result['Locale']['city_name'] . '</option>';
			}
			echo $str;
		}
	}
	function add() {
		$session = (isset($_COOKIE['tmls_uniq_sess'])) ? $_COOKIE['tmls_uniq_sess'] : '';
		if ($checkData = performAction('sessions', 'check', array($session))) {
			$data = performAction('users', 'fetch', array($checkData['User']['id']));
		}
		
		$this -> render = 0;
		if (isset($_POST['zip'])) {
			$user_id = $data['User']['id'];
			$this -> Locale -> where('city_zip', $_POST['zip']);
			$this -> Locale -> setLimit(1);
			$data = $this -> Locale -> search();
			$zip = $data[0]['Locale']['city_zip'];
			$locale_id = $data[0]['Locale']['id'];
		}
		// add to users_locales
		if ($data['User']['user_type']==1)
			$bool = $this -> Locale -> custom("INSERT IGNORE INTO `locales_users` (`user_id`,`locale_id`,`agent`) VALUES ('$user_id','$locale_id',1)", true);
		else
			$bool = $this -> Locale -> custom("INSERT IGNORE INTO `locales_users` (`user_id`,`locale_id`) VALUES ('$user_id','$locale_id')", true);
		if ($bool)
			echo $bool . '$' . $data[0]['Locale']['city_name'] . ', ' . $data[0]['Locale']['city_state'];
		else
			echo -1;
	}
	function add_desired($user_id = null, $zipcode = null, $secret = null) {
		$this -> render = 0;
		if (isset($_POST['zip'])) {
			$user_id = $_POST['user_id'];
			$this -> Locale -> where('city_zip', $_POST['zip']);
			$this -> Locale -> setLimit(1);
			$data = $this -> Locale -> search();
			$zip = $data[0]['Locale']['city_zip'];
			$locale_id = $data[0]['Locale']['id'];
		} elseif (isset($zipcode) && isset($user_id) && isset($secret)) {
			$code = md5('test_code#');
			if ($secret == $code) {
				$this -> Locale -> where('city_zip', $zipcode);
				$this -> Locale -> setLimit(1);
				$data = $this -> Locale -> search();
				$zip = $data[0]['Locale']['city_zip'];
				$locale_id = $data[0]['Locale']['id'];
			}
		}
		// add to users_locales
		if (isset($_POST['is_agent']))
			$bool = $this -> Locale -> custom("INSERT IGNORE INTO `locales_users` (`user_id`,`locale_id`,`agent`) VALUES ('$user_id','$locale_id',1)", true);
		else
			$bool = $this -> Locale -> custom("INSERT IGNORE INTO `locales_users` (`user_id`,`locale_id`) VALUES ('$user_id','$locale_id')", true);
		if ($bool)
			echo $bool . '$' . $data[0]['Locale']['city_name'] . ', ' . $data[0]['Locale']['city_state'];
		else
			echo -1;
	}

	function remove_desired() {
		$this -> render = 0;
		if (isset($_POST['locale_id'])) {
			$id = $_POST['locale_id'];
			$this -> Locale -> custom("DELETE FROM `locales_users` WHERE `id`='$id'");
		}
	}

	function search($locale_query = null, $filter_query = null) {
		$this->doNotRenderHeader = 1;
		$this -> render = 1;
		$raw_lq = $locale_query;
		// Filters
		$f_openTenants = false;

		$locale_array = explode('-', $locale_query);
		if (count($locale_array) == 4) {
			$this -> Locale -> where('city_zip', $locale_array[3]);
		} elseif (count($locale_array) == 3) {
			$this -> Locale -> where('city_state', $locale_array[2]);
			$this -> Locale -> where('city_name', $locale_array[0]);
		} else {
			$this -> Locale -> where('city_zip', $locale_array[0]);
		}
		$this -> Locale -> showHMABTM();
		$data = $this -> Locale -> search();
		#printr($data);
		$raw_filter_array = explode(",", $filter_query);
		$filter_array = array();
		foreach ($raw_filter_array as $filter) {
			$filter = explode('=', $filter);
			if (isset($filter[1])) {
				$filter_array['Filter'][$filter[0]] = $filter[1];
				if ($filter[0] == 'showopen' && $filter[1] == 'true') {
					// tenants open only when logged in as agent
					$session = (isset($_COOKIE['tmls_uniq_sess'])) ? $_COOKIE['tmls_uniq_sess'] : '';
					if ($checkData = performAction('sessions', 'check', array($session))) {
						if ($check['User']['user_type']=='Agent')
							$f_openTenants = true;
					}
				}
			}
		}

		$finalData = array();
		if (count($data) > 0) {
			foreach ($data[0]['User'] as $result) {
				if ($result['locales_users']['agent'] == 0 && ($result['locales_users']['searchable'] || $f_openTenants)) {
					$id = $result['User']['id'];
					$requestArray = array(
						'API_URL'=> BASE_PATH.'/api/select/'.$id,
						'API_ID'=>API_ID,
						'API_SECRET'=>API_SECRET,
						'RETURN_TYPE'=>'json'
					);
					$request = new RestRequest($requestArray['API_URL'],'POST',$requestArray);
					$request->execute();
					$api_data = json_decode($request->getResponseBody(),true);

					// Push the final results
					array_push($finalData, $api_data);
				}
			}
		}
		$this->set('query',$raw_lq);
		$this -> set('data', $finalData);
		$this -> set('filter_data', $filter_array);
		//echo '<pre>';print_r($finalData);echo'</pre>';
	}

	function grab($sorted_by = 'newest') {
		if ($checkData = performAction('sessions', 'check', array($_COOKIE['tmls_uniq_sess']))) {
			$str = '';
			$userArray = array();
			$data = performAction('users', 'fetch', array($checkData['User']['id']));
			foreach ($data['Locale'] as $locale) {
				$this -> Locale -> id = $locale['Locale']['id'];
				$this -> Locale -> showHMABTM();
				$data = $this -> Locale -> search();
				foreach ($data['User'] as $user) {
					if (!in_array($user['User']['id'], $userArray) && $user['User']['user_type'] == 0) {
						array_push($userArray, $user['User']['id']);
					}
				}
			}
			foreach ($userArray as $user) {
				$data = performAction('users', 'fetch', array($user));
				$str .= '<li>';
				if (count($data['Picture']) > 0) {
					foreach ($data['Picture'] as $picture) {
						if ($picture['Picture']['default'])
							$str .= '<img src="' . BASE_PATH . '/uploads/' . $data['User']['id'] . '/' . $picture['Picture']['file_name'] . '_thumb.jpg" class="tenant-picture" />';
					}

				} else {
					$str .= '<img src="' . BASE_PATH . '/images/ui-default-noPic.png" class="tenant-picture">';
				}
				$str .= '<a href="' . BASE_PATH . '/users/view/' . $data['User']['tmls_number'] . '/">' . $data['Profile']['first_name'] . ' ' . $data['Profile']['last_name'] . '</a>';
				$str .= '<h5>';
				$num_occupants = $data['Profile']['tenant_occupants_adults'] + $data['Profile']['tenant_occupants_children'];
				$str .= '$' . $data['Profile']['tenant_max_rent'] . ' - <img src="/images/ui-occupants-icon.png" class="ui-icon" /> ' . $num_occupants . ' - ' . $data['Profile']['tenant_desired_beds'] . 'BR';
				$str .= '</h5>';
				$str .= '</li>';
			}
			return $str;
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
