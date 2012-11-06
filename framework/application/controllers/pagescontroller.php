<?php
class PagesController extends Controller {
	function beforeAction() {
		session_start();
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


			$this->set('tmls_setting_loggedIn',true);
			$this->set('tmls_setting_sessionTmlsNumber',$data['Session']['user_id']);
			$this->set('tmls_user', $json_apidata);
		} else {
			$this->set('tmls_setting_loggedIn',false);
			$this->set('tmls_setting_sessionTmlsNumber',false);
		}
	}
	function afterAction() {
		
	}
	function developer() {
		
	}
	function about() {
		
	}
	
	function fourohfour() {
	}
	
	function error($code = '404') {
		$this->render = 1;
		$this->set('code',$code);
	}
}
?>