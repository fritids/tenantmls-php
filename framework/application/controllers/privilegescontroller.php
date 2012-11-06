<?php
class PrivilegesController extends Controller {
	function beforeAction() {
		
	}	
	function afterAction() {
		
	}
	function create() {
		if (isset($_POST['from_jid'])) {
			// unset the request
			$request_id = $_POST['request_id'];
			$this->Privilege->custom("DELETE FROM `requests` WHERE `id`='$request_id'");
			$this->Privilege->custom("DELETE FROM `requests_users` WHERE `request_id`='$request_id'");

			$from_jid = $_POST['from_jid'];
			$to_jid = $_POST['to_jid'];

			$from_array = explode('@',$from_jid);
			$to_array = explode('@',$to_jid);

			$from_tmls = $from_array[0];
			$to_tmls = $to_array[0];

			$user_id = $from_tmls;
			$privileges = explode(',',array(1,1,1));
			$this->Privilege->from_id = $from_tmls;
			$this->Privilege->to_id = $to_tmls;
			$this->Privilege->view = $privileges[0];
			$this->Privilege->edit = $privileges[1];
			$this->Privilege->upload = $privileges[2];
			if ($_POST['exclusive']=='X') {
				// remove the other privileges for the user
			} else
				$this->Privilege->exclusive = 0;
			$privilege_id = $this->Privilege->save(true);
			
			$this->Privilege->custom("INSERT INTO `privileges_users` (`user_id`,`privilege_id`) VALUES ('$from_tmls','$privilege_id')");
			$this->Privilege->custom("INSERT INTO `privileges_users` (`user_id`,`privilege_id`) VALUES ('$to_tmls','$privilege_id')");
			$this->Privilege->custom("UPDATE `locales_users` SET `searchable`=1 WHERE `user_id`='$to_tmls' OR `user_id`='$from_tmls'");
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