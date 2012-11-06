<?php
class MessagesController extends Controller {
	function beforeAction() {
		require_once (ROOT . DS . 'library' . DS . 'XMPPHP' . DS . 'XMPP.php');
	}
	function afterAction() {
		
	}
	function send_message($to, $from, $text, $action = null) {
	}
	function chat() {
		$this->doNotRenderHeader = 1;

	}
        
    function inbox() {
        
    }
		
	function send_to($user_id = null) {
		$this->doNotRenderHeader = 1;
	}
	
	function test() {
		$this->Message->id = 20;
			$this->Message->showHMABTM();
			echo '<pre>';print_r($data = $this->Message->search());echo '</pre>';
			echo $data['User'][0]['User']['id'];
	}
	
	function request_tenant($user_id = null){
		$this->doNotRenderHeader = 1;
		$this->set('xUserId',$user_id);
		if (isset($_COOKIE['tmls_uniq_sess']) && isset($_POST['post_request'])) {
			$data = performAction('users','check_user',array($_COOKIE['tmls_uniq_sess']));
			// Set user_id to person logged in
			$this->Message->user_id = $data[0]['User']['id'];
			$this->Message->body = $_POST['body'];
			$this->Message->request_type = $_POST['request_type'];
			$this->Message->request_commission = $_POST['request_commission'];
			$this->Message->request_privileges = $_POST['request_privileges'];
			$this->Message->logged = date("Y-m-d H:i:s");
			$message_id = $this->Message->save(true);
			$this->Message->custom("INSERT INTO `messages_users` (`user_id`,`message_id`) VALUES ('$user_id','$message_id')");
		}
	}
	
	function respond($granted = false) {
		$this->doNotRenderHeader = 1;
		if (isset($_POST['message_id'])) {
			$this->Message->id = $_POST['message_id'];
			$this->Message->hide_request = 1;
			$this->Message->read = 1;
			$this->Message->save();
			//
			$this->Message->id = $_POST['message_id'];
			$this->Message->showHMABTM();
			$data = $this->Message->search();
			// Send a response message
			$this->Message->user_id = $data['User'][0]['User']['id'];
			if ($granted) {
				$privileges = explode(',',$_POST['privileges']);
				$str_privileges = '';
				$str_privileges.= ($privileges[0]==1) ? '<b>View</b>, ' : '';
				$str_privileges.= ($privileges[1]==1) ? '<b>Edit</b>, ' : '';
				$str_privileges.= ($privileges[2]==1) ? '<b>Upload</b>, ' : '';
				if ($privileges[0]==0 && $privileges[1]==0 && $privileges[2]==0) {
					$str_privileges = 'None, ';
				}
				$this->Message->title = performAction('users','fetch',array($data['User'][0]['User']['id'],'name'))." has accepted your request for tenancy";
				$this->Message->body = 'The following privileges have been granted: '.substr($str_privileges,0,strlen($str_privileges)-2);
			} else {
				$this->Message->title = performAction('users','fetch',array($data['User'][0]['User']['id'],'name'))." has denied your request for tenancy";
				$this->Message->body = 'You can ignore or reply to this message';	
			}
			$this->Message->logged = date("Y-m-d H:i:s");
			$message_id = $this->Message->save(true);
			$grant_id = $_POST['grant_id'];
			$this->Message->custom("INSERT INTO `messages_users` (`user_id`,`message_id`) VALUES ('$grant_id','$message_id')");
		}
	}
}
?>