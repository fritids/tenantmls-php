<?php
class RequestsController extends Controller {
	function beforeAction() {

	}
	function afterAction() {

	}

	function respond($request_id = null) {
		$this->doNotRenderHeader = 1;
		$session = (isset($_COOKIE['tmls_uniq_sess'])) ? $_COOKIE['tmls_uniq_sess'] : '';
		if ((!empty($id) && is_numeric(substr($id, 1, strlen($id) - 1))) || ((empty($id) || !is_numeric($id)) && $data = performAction('sessions', 'check', array($session)))) {
			$id = ((!empty($id) && is_numeric(substr($id, 1, strlen($id) - 1)))) ? $id : $data['User']['id'];
			$requestArray = array(
				'API_URL'=> BASE_PATH.'/api/select/'.$id.'/custom',
				'API_ID'=>API_ID,
				'API_SECRET'=>API_SECRET,
				'USER_SESSION'=>$session,
				'RETURN_TYPE'=>'json',
				'OPTION_INCLUDE_USER'=>true,
				'OPTION_INCLUDE_PROFILE'=>true,
				'OPTION_INCLUDE_REQUEST'=>true
			);
			$request = new RestRequest($requestArray['API_URL'],'POST',$requestArray);
			$request->execute();
			$json_apidata = json_decode($request->getResponseBody(),true);
			$this->set('tmls_user',$json_apidata['User']);
			foreach($json_apidata['Request'] as $request) {
				if ($request['Request']['id']==$request_id)
					$this -> set('request', $request);
			}
		} else {
			header("HTTP/1.0 404 Not Found");
		}
	}

	function add() {
		$this->render = 0;
		if (isset($_POST['jid'])) {
			$from_jid = $_POST['request_jid'];
			$to_jid = $_POST['jid'];

			$from_array = explode('@',$from_jid);
			$to_array = explode('@',$to_jid);

			$from_tmls = $from_array[0];
			$to_tmls = $to_array[0];

			$this->Request->from_id = $from_tmls;
			$this->Request->to_id = $to_tmls;
			$this->Request->logged = date("Y-m-d H:i:s");
			$this->Request->request_type = $_POST['agency_type'];
			$request_id = $this->Request->save(true);

			$this->Request->custom("INSERT INTO `requests_users` (`user_id`,`request_id`) VALUES ('$from_tmls','$request_id')");
			$this->Request->custom("INSERT INTO `requests_users` (`user_id`,`request_id`) VALUES ('$to_tmls','$request_id')");
		}
	}
}
?>